<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenghasilanController extends Controller
{

    // Get all penghasilan with filters
    public function index(Request $request)
    {
        $query = Penghasilan::query();

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter by month
        if ($request->has('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        // Filter by year
        if ($request->has('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        // Search by plat nomor or nama pemilik
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $penghasilan = $query->orderBy('tanggal', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $penghasilan,
            'total' => $penghasilan->total(),
            'summary' => [
                'total_pendapatan' => $query->sum('total'),
                'total_transaksi' => $query->count(),
            ]
        ]);
    }

    // Get single penghasilan
    public function show($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $penghasilan
        ]);
    }

    // Store new penghasilan
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'plat_nomor' => 'required|string|max:20',
            'nama_pemilik' => 'required|string|max:100',
            'service' => 'required|string',
            'sparepart' => 'nullable|string',
            'harga_sparepart' => 'nullable|numeric|min:0',
            'biaya_jasa' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $penghasilan = Penghasilan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Penghasilan berhasil ditambahkan',
            'data' => $penghasilan
        ], 201);
    }

    // Update penghasilan
    public function update(Request $request, $id)
    {
        $penghasilan = Penghasilan::findOrFail($id);

        $request->validate([
            'tanggal' => 'sometimes|date',
            'plat_nomor' => 'sometimes|string|max:20',
            'nama_pemilik' => 'sometimes|string|max:100',
            'service' => 'sometimes|string',
            'sparepart' => 'nullable|string',
            'harga_sparepart' => 'nullable|numeric|min:0',
            'biaya_jasa' => 'nullable|numeric|min:0',
            'total' => 'sometimes|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $penghasilan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Penghasilan berhasil diupdate',
            'data' => $penghasilan
        ]);
    }

    // Delete penghasilan
    public function destroy($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        $penghasilan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penghasilan berhasil dihapus'
        ]);
    }

    // Get statistics
    public function statistics(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        $query = Penghasilan::whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
        }

        $total = $query->sum('total');
        $count = $query->count();
        $average = $count > 0 ? $total / $count : 0;

        // Monthly breakdown
        $monthly = Penghasilan::whereYear('tanggal', $year)
            ->select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('MONTH(tanggal)'))
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'count' => $count,
                'average' => $average,
                'monthly' => $monthly
            ]
        ]);
    }
}