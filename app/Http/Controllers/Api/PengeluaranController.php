<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{

    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->has('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->has('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $query->where('keterangan', 'like', "%{$request->search}%");
        }

        $perPage = $request->get('per_page', 15);
        $pengeluaran = $query->orderBy('tanggal', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pengeluaran,
            'total' => $pengeluaran->total(),
            'summary' => [
                'total_pengeluaran' => $query->sum('nominal'),
                'total_transaksi' => $query->count(),
            ]
        ]);
    }

    public function show($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $pengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|in:Stok Barang,Operasional,Peralatan,Lainnya',
            'nominal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengeluaran = Pengeluaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil ditambahkan',
            'data' => $pengeluaran
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $request->validate([
            'tanggal' => 'sometimes|date',
            'keterangan' => 'sometimes|string|max:255',
            'kategori' => 'sometimes|in:Stok Barang,Operasional,Peralatan,Lainnya',
            'nominal' => 'sometimes|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengeluaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diupdate',
            'data' => $pengeluaran
        ]);
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus'
        ]);
    }

    public function statistics(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $kategori = $request->get('kategori');

        $query = Pengeluaran::whereYear('tanggal', $year);
        
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $total = $query->sum('nominal');
        $count = $query->count();

        // Breakdown by category
        $byCategory = Pengeluaran::whereYear('tanggal', $year)
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'count' => $count,
                'by_category' => $byCategory
            ]
        ]);
    }
}