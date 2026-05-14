<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function overview(Request $request)
    {
        $period = $request->get('period', 30);
        $startDate = now()->subDays($period);

        $totalPenghasilan = Penghasilan::whereBetween('tanggal', [$startDate, now()])->sum('total');
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, now()])->sum('nominal');
        $labaBersih = $totalPenghasilan - $totalPengeluaran;
        
        // Percentage changes
        $prevStartDate = now()->subDays($period * 2);
        $prevEndDate = now()->subDays($period);
        
        $prevPenghasilan = Penghasilan::whereBetween('tanggal', [$prevStartDate, $prevEndDate])->sum('total');
        $prevPengeluaran = Pengeluaran::whereBetween('tanggal', [$prevStartDate, $prevEndDate])->sum('nominal');
        
        $penghasilanChange = $prevPenghasilan > 0 
            ? (($totalPenghasilan - $prevPenghasilan) / $prevPenghasilan) * 100 
            : 0;
        
        $pengeluaranChange = $prevPengeluaran > 0 
            ? (($totalPengeluaran - $prevPengeluaran) / $prevPengeluaran) * 100 
            : 0;

        // Stock summary
        $totalProduk = Produk::count();
        $lowStock = Produk::where('stok', '<', 10)->where('stok', '>', 0)->count();
        $outOfStock = Produk::where('stok', '<=', 0)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'total_penghasilan' => $totalPenghasilan,
                'total_pengeluaran' => $totalPengeluaran,
                'laba_bersih' => $labaBersih,
                'penghasilan_change' => round($penghasilanChange, 1),
                'pengeluaran_change' => round($pengeluaranChange, 1),
                'total_produk' => $totalProduk,
                'low_stock' => $lowStock,
                'out_of_stock' => $outOfStock,
            ]
        ]);
    }

    public function chart(Request $request)
    {
        $period = $request->get('period', 30);
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            
            $income = Penghasilan::whereDate('tanggal', $date->format('Y-m-d'))->sum('total');
            $expense = Pengeluaran::whereDate('tanggal', $date->format('Y-m-d'))->sum('nominal');
            
            $incomeData[] = round($income / 1000);
            $expenseData[] = round($expense / 1000);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'penghasilan' => $incomeData,
                'pengeluaran' => $expenseData,
                'income' => $incomeData,
                'expense' => $expenseData,
            ]
        ]);
    }

    public function recentTransactions(Request $request)
    {
        $limit = $request->get('limit', 10);
        $hasPenghasilanSlug = \Illuminate\Support\Facades\Schema::hasColumn('penghasilan', 'slug');
        $hasPengeluaranSlug = \Illuminate\Support\Facades\Schema::hasColumn('pengeluaran', 'slug');
        
        $penghasilan = Penghasilan::select(
                'id',
                $hasPenghasilanSlug ? 'slug' : 'id as slug',
                'tanggal',
                'created_at',
                DB::raw("'Penghasilan' as kategori"),
                'service as keterangan',
                'total as nominal',
                DB::raw("'income' as type")
            )
            ->latest('created_at')
            ->limit($limit)
            ->get();
        
        $pengeluaran = Pengeluaran::select(
                'id',
                $hasPengeluaranSlug ? 'slug' : 'id as slug',
                'tanggal',
                'created_at',
                DB::raw("'Pengeluaran' as kategori"),
                'keterangan',
                'nominal',
                DB::raw("'expense' as type")
            )
            ->latest('created_at')
            ->limit($limit)
            ->get();
        
        $transactions = $penghasilan->concat($pengeluaran)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function notifications()
    {
        $notifications = [];

        // 1. Stok Menipis & Habis
        $lowStock = Produk::where('stok', '<', 10)->get();
        foreach ($lowStock as $p) {
            $type = $p->stok <= 0 ? 'danger' : 'warning';
            $msg = $p->stok <= 0 ? 'Stok produk habis dan perlu segera diisi ulang.' : 'Sisa stok hanya ' . $p->stok . ' unit.';
            $notifications[] = [
                'id' => 'p_' . $p->id,
                'title' => ($p->stok <= 0 ? 'Stok Habis: ' : 'Stok Menipis: ') . $p->nama,
                'message' => $msg,
                'type' => $type,
                'time' => $p->updated_at ? $p->updated_at->format('d M Y H:i') : now()->format('d M Y H:i'),
                'timestamp' => $p->updated_at ? $p->updated_at->timestamp : now()->timestamp,
            ];
        }

        // 2. Transaksi Pemasukan Terbaru
        $penghasilan = Penghasilan::latest('created_at')->take(5)->get();
        foreach ($penghasilan as $p) {
            $notifications[] = [
                'id' => 'inc_' . $p->id,
                'title' => 'Pemasukan Baru: ' . $p->service,
                'message' => 'Tercatat pemasukan sebesar Rp ' . number_format($p->total, 0, ',', '.'),
                'type' => 'success',
                'time' => $p->created_at ? $p->created_at->format('d M Y H:i') : now()->format('d M Y H:i'),
                'timestamp' => $p->created_at ? $p->created_at->timestamp : now()->timestamp,
            ];
        }

        // 3. Transaksi Pengeluaran Terbaru
        $pengeluaran = Pengeluaran::latest('created_at')->take(5)->get();
        foreach ($pengeluaran as $p) {
            $notifications[] = [
                'id' => 'exp_' . $p->id,
                'title' => 'Pengeluaran Bengkel: ' . $p->keterangan,
                'message' => 'Tercatat pengeluaran sebesar Rp ' . number_format($p->nominal, 0, ',', '.'),
                'type' => 'info',
                'time' => $p->created_at ? $p->created_at->format('d M Y H:i') : now()->format('d M Y H:i'),
                'timestamp' => $p->created_at ? $p->created_at->timestamp : now()->timestamp,
            ];
        }

        // Urutkan berdasarkan timestamp terbaru
        usort($notifications, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return response()->json([
            'success' => true,
            'data' => array_slice($notifications, 0, 15)
        ]);
    }
}