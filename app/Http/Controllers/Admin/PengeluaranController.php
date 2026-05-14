<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengeluaran::query();
        
        // Filter berdasarkan periode
        $period = $request->get('period', 'bulan_ini');
        $filterText = 'Semua Data';
        
        switch ($period) {
            case 'bulan_ini':
                $query->whereMonth('tanggal', date('m'))
                    ->whereYear('tanggal', date('Y'));
                $filterText = 'Bulan Ini';
                break;
            case 'minggu_ini':
                $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                $filterText = 'Minggu Ini';
                break;
            case '30_hari':
                $query->where('tanggal', '>=', now()->subDays(30));
                $filterText = '30 Hari Terakhir';
                break;
            default:
                break;
        }
        
        // Pencarian
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
            });
        }
        
        // Ambil SEMUA data untuk statistik (tanpa pagination)
        $allData = $query->get();
        
        // Hitung statistik dari collection
        $totalPengeluaran = $allData->sum('nominal');
        $totalTransaksi = $allData->count();
        $totalStokBarang = $allData->where('kategori', 'Stok Barang')->sum('nominal');
        $totalOperasional = $allData->where('kategori', 'Operasional')->sum('nominal');
        
        // Ambil data untuk pagination (dengan order)
        $pengeluaran = $query->orderBy('tanggal', 'desc')->paginate(10);
        
        return view('admin.pengeluaran.index', compact(
            'pengeluaran', 
            'totalPengeluaran', 
            'totalTransaksi', 
            'totalStokBarang', 
            'totalOperasional', 
            'filterText', 
            'period'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengeluaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|in:Stok Barang,Operasional,Peralatan,Lainnya',
            'nominal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengeluaran = Pengeluaran::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil disimpan',
                'data' => $pengeluaran
            ]);
        }

        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengeluaran = Pengeluaran::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        return view('admin.pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|in:Stok Barang,Operasional,Peralatan,Lainnya',
            'nominal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengeluaran->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil diperbarui',
                'data' => $pengeluaran
            ]);
        }

        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $pengeluaran->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        $query = Pengeluaran::query();
        
        if ($request->get('period')) {
            switch ($request->period) {
                case 'bulan_ini':
                    $query->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case '30_hari':
                    $query->where('tanggal', '>=', now()->subDays(30));
                    break;
            }
        }
        
        if ($search = $request->get('search')) {
            $query->where('keterangan', 'like', "%{$search}%");
        }
        
        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();
        
        return response()->json($pengeluaran);
    }

    /**
     * Export to PDF
     */
    public function exportPDF(Request $request)
    {
        $query = Pengeluaran::query();
        
        $period = $request->get('period', 'semua');
        $filterText = 'Semua Data';
        
        switch ($period) {
            case 'bulan_ini':
                $query->whereMonth('tanggal', date('m'))
                      ->whereYear('tanggal', date('Y'));
                $filterText = 'Bulan ' . date('F Y');
                break;
            case 'minggu_ini':
                $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                $filterText = 'Minggu Ini (' . now()->startOfWeek()->format('d M Y') . ' - ' . now()->endOfWeek()->format('d M Y') . ')';
                break;
            case '30_hari':
                $query->where('tanggal', '>=', now()->subDays(30));
                $filterText = '30 Hari Terakhir';
                break;
            default:
                break;
        }
        
        if ($search = $request->get('search')) {
            $query->where('keterangan', 'like', "%{$search}%");
        }
        
        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();
        
        $totalPengeluaran = $query->sum('nominal');
        $totalTransaksi = $query->count();
        $totalStokBarang = $query->where('kategori', 'Stok Barang')->sum('nominal');
        $totalOperasional = $query->where('kategori', 'Operasional')->sum('nominal');
        
        $data = [
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'totalTransaksi' => $totalTransaksi,
            'totalStokBarang' => $totalStokBarang,
            'totalOperasional' => $totalOperasional,
            'filterText' => $filterText,
            'period' => $period,
            'exportDate' => now()->format('d F Y H:i:s'),
            'search' => $request->get('search')
        ];
        
        $pdf = Pdf::loadView('admin.pengeluaran.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-pengeluaran-' . date('Y-m-d-His') . '.pdf');
    }
}