<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{

    // Main statistics dashboard
    public function index(Request $request)
    {
        $period = $request->get('period', 30);
        
        $summary = $this->getSummaryData($period);
        $trendData = $this->getTrendData($period);
        $distribution = $this->getDistributionData($period);
        $monthlyComparison = $this->getMonthlyComparison();
        $topServices = $this->getTopServices($period);
        
        return response()->json([
            'success' => true,
            'period' => $period,
            'data' => [
                'summary' => $summary,
                'trend' => $trendData,
                'distribution' => $distribution,
                'monthly_comparison' => $monthlyComparison,
                'top_services' => $topServices
            ]
        ]);
    }

    // Endpoint for mobile summary
    public function summary(Request $request)
    {
        $period = $request->get('period', 30);
        $summary = $this->getSummaryData($period);
        $topServices = $this->getTopServices($period);

        return response()->json(array_merge($summary, [
            'success' => true,
            'top_services' => $topServices
        ]));
    }

    // Endpoint for mobile trend
    public function trend(Request $request)
    {
        $period = $request->get('period', 30);
        $trendData = $this->getTrendData($period);

        return response()->json(array_merge($trendData, [
            'success' => true
        ]));
    }

    // Endpoint for mobile chart / distribution
    public function chart(Request $request)
    {
        $period = $request->get('period', 30);
        $distribution = $this->getDistributionData($period);

        return response()->json([
            'success' => true,
            'distribution' => $distribution
        ]);
    }

    // Endpoint for mobile top services
    public function topServices(Request $request)
    {
        $period = $request->get('period', 30);
        $topServices = $this->getTopServices($period);

        return response()->json([
            'success' => true,
            'data' => $topServices
        ]);
    }

    // Get revenue statistics
    public function revenue(Request $request)
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
        
        // Daily breakdown for selected period
        $daily = Penghasilan::whereYear('tanggal', $year)
            ->when($month, function($q) use ($month) {
                $q->whereMonth('tanggal', $month);
            })
            ->select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $total,
                'total_transactions' => $count,
                'average_per_transaction' => $average,
                'daily_breakdown' => $daily,
                'year' => $year,
                'month' => $month
            ]
        ]);
    }

    // Get expense statistics
    public function expense(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        $query = Pengeluaran::whereYear('tanggal', $year);
        
        if ($month) {
            $query->whereMonth('tanggal', $month);
        }
        
        $total = $query->sum('nominal');
        $count = $query->count();
        
        // Breakdown by category
        $byCategory = Pengeluaran::whereYear('tanggal', $year)
            ->when($month, function($q) use ($month) {
                $q->whereMonth('tanggal', $month);
            })
            ->select('kategori', DB::raw('SUM(nominal) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('kategori')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_expense' => $total,
                'total_transactions' => $count,
                'by_category' => $byCategory,
                'year' => $year,
                'month' => $month
            ]
        ]);
    }

    // Get profit/loss statistics
    public function profit(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        $revenue = Penghasilan::whereYear('tanggal', $year)
            ->when($month, function($q) use ($month) {
                $q->whereMonth('tanggal', $month);
            })
            ->sum('total');
        
        $expense = Pengeluaran::whereYear('tanggal', $year)
            ->when($month, function($q) use ($month) {
                $q->whereMonth('tanggal', $month);
            })
            ->sum('nominal');
        
        $profit = $revenue - $expense;
        $margin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;
        
        // Monthly profit trend
        $monthlyTrend = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthRevenue = Penghasilan::whereYear('tanggal', $year)->whereMonth('tanggal', $i)->sum('total');
            $monthExpense = Pengeluaran::whereYear('tanggal', $year)->whereMonth('tanggal', $i)->sum('nominal');
            $monthlyTrend[] = [
                'month' => $i,
                'month_name' => \Carbon\Carbon::create()->month($i)->translatedFormat('F'),
                'revenue' => $monthRevenue,
                'expense' => $monthExpense,
                'profit' => $monthRevenue - $monthExpense
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $revenue,
                'total_expense' => $expense,
                'net_profit' => $profit,
                'profit_margin' => round($margin, 2),
                'year' => $year,
                'month' => $month,
                'monthly_trend' => $monthlyTrend
            ]
        ]);
    }

    // Get product statistics
    public function products(Request $request)
    {
        $totalProducts = Produk::count();
        $totalValue = Produk::sum(DB::raw('harga * stok'));
        $lowStock = Produk::where('stok', '<', 10)->count();
        $outOfStock = Produk::where('stok', '<=', 0)->count();
        
        // Stock by category
        $byCategory = Produk::select('kategori', 
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(stok) as total_stock'),
            DB::raw('SUM(harga * stok) as total_value')
        )
        ->groupBy('kategori')
        ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'total_inventory_value' => $totalValue,
                'low_stock_products' => $lowStock,
                'out_of_stock_products' => $outOfStock,
                'healthy_stock_products' => $totalProducts - ($lowStock + $outOfStock),
                'by_category' => $byCategory
            ]
        ]);
    }

    // Export statistics as PDF (returns JSON with data for PDF generation)
    public function export(Request $request)
    {
        $period = $request->get('period', 30);
        
        $summary = $this->getSummaryData($period);
        $trendData = $this->getTrendData($period);
        $monthlyComparison = $this->getMonthlyComparison();
        
        return response()->json([
            'success' => true,
            'data' => [
                'generated_at' => now()->toDateTimeString(),
                'period' => $period,
                'summary' => $summary,
                'trend' => $trendData,
                'monthly_comparison' => $monthlyComparison
            ]
        ]);
    }

    private function getSummaryData($period)
    {
        $startDate = now()->subDays($period);
        
        $totalPenghasilan = Penghasilan::whereBetween('tanggal', [$startDate, now()])->sum('total');
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, now()])->sum('nominal');
        $labaBersih = $totalPenghasilan - $totalPengeluaran;
        $marginProfit = $totalPenghasilan > 0 ? ($labaBersih / $totalPenghasilan) * 100 : 0;
        
        // Previous period for comparison
        $prevStart = now()->subDays($period * 2);
        $prevEnd = now()->subDays($period);
        
        $prevPenghasilan = Penghasilan::whereBetween('tanggal', [$prevStart, $prevEnd])->sum('total');
        $prevPengeluaran = Pengeluaran::whereBetween('tanggal', [$prevStart, $prevEnd])->sum('nominal');
        
        $penghasilanChange = $prevPenghasilan > 0 ? (($totalPenghasilan - $prevPenghasilan) / $prevPenghasilan) * 100 : 0;
        $pengeluaranChange = $prevPengeluaran > 0 ? (($totalPengeluaran - $prevPengeluaran) / $prevPengeluaran) * 100 : 0;
        
        return [
            'total_penghasilan' => $totalPenghasilan,
            'total_pengeluaran' => $totalPengeluaran,
            'laba_bersih' => $labaBersih,
            'margin_profit' => round($marginProfit, 1),
            'penghasilan_change' => round($penghasilanChange, 1),
            'pengeluaran_change' => round($pengeluaranChange, 1)
        ];
    }

    private function getTrendData($period)
    {
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
        
        return [
            'labels' => $labels,
            'penghasilan' => $incomeData,
            'pengeluaran' => $expenseData,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    private function getDistributionData($period)
    {
        $startDate = now()->subDays($period);
        
        $pengeluaranByKategori = Pengeluaran::whereBetween('tanggal', [$startDate, now()])
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();
        
        $total = $pengeluaranByKategori->sum('total');
        
        return $pengeluaranByKategori->map(function($item) use ($total) {
            return [
                'category' => $item->kategori,
                'total' => $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0
            ];
        });
    }

    private function getMonthlyComparison()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $income = Penghasilan::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('total');
            $expense = Pengeluaran::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('nominal');
            
            $data[] = [
                'month' => $month->translatedFormat('M Y'),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense
            ];
        }
        
        return $data;
    }

    private function getTopServices($period)
    {
        $startDate = now()->subDays($period);
        
        $services = Penghasilan::whereBetween('tanggal', [$startDate, now()])
            ->select('service', DB::raw('COUNT(*) as total'))
            ->groupBy('service')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        $maxTotal = $services->max('total') ?: 1;
        
        return $services->map(function($item) use ($maxTotal) {
            return [
                'name' => $item->service,
                'count' => $item->total,
                'percentage' => round(($item->total / $maxTotal) * 100)
            ];
        });
    }
}