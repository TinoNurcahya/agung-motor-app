<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Periode untuk chart (default 30 hari)
        $period = $request->get('period', 30);
        
        // Data untuk overview cards
        $overview = $this->getOverviewData($period);
        
        // Data untuk chart
        $chartData = $this->getChartData($period);
        
        // Data untuk transaksi terakhir
        $recentTransactions = $this->getRecentTransactions();
        
        return view('admin.dashboard.index', compact('overview', 'chartData', 'recentTransactions', 'period'));
    }
    
    private function getOverviewData($period = 30)
    {
        $startDate = now()->subDays($period);
        
        // Total Penghasilan periode ini
        $totalPenghasilan = Penghasilan::whereBetween('tanggal', [$startDate, now()])->sum('total');
        
        // Total Pengeluaran periode ini
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, now()])->sum('nominal');
        
        // Laba Bersih
        $labaBersih = $totalPenghasilan - $totalPengeluaran;
        
        // Hitung persentase perubahan dari periode sebelumnya
        $previousStartDate = now()->subDays($period * 2);
        $previousEndDate = now()->subDays($period);
        
        $prevPenghasilan = Penghasilan::whereBetween('tanggal', [$previousStartDate, $previousEndDate])->sum('total');
        $prevPengeluaran = Pengeluaran::whereBetween('tanggal', [$previousStartDate, $previousEndDate])->sum('nominal');
        
        $penghasilanChange = $prevPenghasilan > 0 
            ? (($totalPenghasilan - $prevPenghasilan) / $prevPenghasilan) * 100 
            : 0;
        
        $pengeluaranChange = $prevPengeluaran > 0 
            ? (($totalPengeluaran - $prevPengeluaran) / $prevPengeluaran) * 100 
            : 0;
        
        return [
            'total_penghasilan' => $totalPenghasilan,
            'total_pengeluaran' => $totalPengeluaran,
            'laba_bersih' => $labaBersih,
            'penghasilan_change' => $penghasilanChange,
            'pengeluaran_change' => $pengeluaranChange,
        ];
    }
    
    private function getChartData($period = 30)
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        
        // Group data per hari atau per minggu berdasarkan periode
        if ($period <= 30) {
            // Tampilkan per hari untuk periode pendek
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d M');
                
                $income = Penghasilan::whereDate('tanggal', $date->format('Y-m-d'))->sum('total');
                $expense = Pengeluaran::whereDate('tanggal', $date->format('Y-m-d'))->sum('nominal');
                
                $incomeData[] = round($income / 1000); // dalam ribuan
                $expenseData[] = round($expense / 1000);
            }
        } else {
            // Tampilkan per minggu untuk periode panjang
            $weeks = ceil($period / 7);
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = now()->subWeeks($i)->endOfWeek();
                $labels[] = 'Minggu ' . ($weeks - $i);
                
                $income = Penghasilan::whereBetween('tanggal', [$weekStart, $weekEnd])->sum('total');
                $expense = Pengeluaran::whereBetween('tanggal', [$weekStart, $weekEnd])->sum('nominal');
                
                $incomeData[] = round($income / 1000);
                $expenseData[] = round($expense / 1000);
            }
        }
        
        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
    
    private function getRecentTransactions($limit = 5)
    {
        $hasPenghasilanSlug = \Illuminate\Support\Facades\Schema::hasColumn('penghasilan', 'slug');
        $hasPengeluaranSlug = \Illuminate\Support\Facades\Schema::hasColumn('pengeluaran', 'slug');

        // Gabungkan penghasilan dan pengeluaran
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
            ->get();
        
        // Gabungkan dan urutkan berdasarkan tanggal
        $transactions = $penghasilan->concat($pengeluaran)
            ->sortByDesc('created_at')
            ->take($limit);
        
        return $transactions;
    }
    
    // API endpoint untuk refresh chart (AJAX)
    public function refreshChart(Request $request)
    {
        $period = $request->get('period', 30);
        $chartData = $this->getChartData($period);
        
        return response()->json([
            'success' => true,
            'data' => $chartData
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
                'url' => route('admin.produk.show', $p->slug ?: $p->id),
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
                'url' => route('admin.penghasilan.show', $p->slug ?: $p->id),
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
                'url' => route('admin.pengeluaran.show', $p->slug ?: $p->id),
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