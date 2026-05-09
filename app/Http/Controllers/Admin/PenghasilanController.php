<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PenghasilanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penghasilan::query();
        
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
                // Semua data
                break;
        }
        
        // Pencarian
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }
        
        // Clone query untuk statistik (sebelum pagination)
        $statsQuery = clone $query;
        
        $penghasilan = $query->orderBy('tanggal', 'desc')->paginate(10);
        
        // Statistik menggunakan query yang sudah di-clone
        $totalPendapatan = $statsQuery->sum('total');
        $totalTransaksi = $statsQuery->count();
        
        // Hitung margin profit (asumsi: profit = total - (harga_sparepart * 0.9) - biaya_jasa)
        $totalModal = $statsQuery->sum(DB::raw('harga_sparepart * 0.9 + biaya_jasa'));
        $marginProfit = $totalPendapatan > 0 
            ? (($totalPendapatan - $totalModal) / $totalPendapatan) * 100 
            : 0;
        
        return view('admin.penghasilan.index', compact('penghasilan', 'totalPendapatan', 'totalTransaksi', 'marginProfit', 'filterText', 'period'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.penghasilan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'plat_nomor' => 'required|string|max:20',
            'nama_pemilik' => 'required|string|max:100',
            'service' => 'required|string',
            'sparepart' => 'nullable|string|max:255',
            'harga_sparepart' => 'nullable|numeric|min:0',
            'biaya_jasa' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $penghasilan = Penghasilan::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => $penghasilan
            ]);
        }

        return redirect()->route('admin.penghasilan.index')
            ->with('success', 'Transaksi penghasilan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        return view('admin.penghasilan.show', compact('penghasilan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        return view('admin.penghasilan.edit', compact('penghasilan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'plat_nomor' => 'required|string|max:20',
            'nama_pemilik' => 'required|string|max:100',
            'service' => 'required|string',
            'sparepart' => 'nullable|string|max:255',
            'harga_sparepart' => 'nullable|numeric|min:0',
            'biaya_jasa' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $penghasilan->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $penghasilan
            ]);
        }

        return redirect()->route('admin.penghasilan.index')
            ->with('success', 'Transaksi penghasilan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        $penghasilan->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.penghasilan.index')
            ->with('success', 'Transaksi penghasilan berhasil dihapus.');
    }

    /**
     * Export to Excel (optional)
     */
    public function export(Request $request)
    {
        $query = Penghasilan::query();
        
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
        
        $penghasilan = $query->orderBy('tanggal', 'desc')->get();
        
        return response()->json($penghasilan);
    }

    /**
     * Export to PDF
     */
    public function exportPDF(Request $request)
    {
        $query = Penghasilan::query();
        
        // Filter berdasarkan periode
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
                // Semua data
                break;
        }
        
        // Pencarian
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }
        
        $penghasilan = $query->orderBy('tanggal', 'desc')->get();
        
        // Statistik
        $totalPendapatan = $query->sum('total');
        $totalTransaksi = $query->count();
        $totalHargaSparepart = $query->sum('harga_sparepart');
        $totalBiayaJasa = $query->sum('biaya_jasa');
        
        // Data untuk PDF
        $data = [
            'penghasilan' => $penghasilan,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi' => $totalTransaksi,
            'totalHargaSparepart' => $totalHargaSparepart,
            'totalBiayaJasa' => $totalBiayaJasa,
            'filterText' => $filterText,
            'period' => $period,
            'exportDate' => now()->format('d F Y H:i:s'),
            'search' => $request->get('search')
        ];
        
        $pdf = Pdf::loadView('admin.penghasilan.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-penghasilan-' . date('Y-m-d-His') . '.pdf');
    }
}