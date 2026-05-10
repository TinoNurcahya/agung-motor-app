<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 30); // default 30 hari
        
        // Data untuk summary cards
        $summary = $this->getSummaryData($period);
        
        // Data untuk trend chart
        $trendData = $this->getTrendData($period);
        
        // Data untuk doughnut chart (distribusi pengeluaran)
        $doughnutData = $this->getDoughnutData($period);
        
        // Data untuk bar chart (perbandingan bulanan)
        $barChartData = $this->getBarChartData();
        
        // Data untuk top services
        $topServices = $this->getTopServices($period);
        
        return view('admin.statistik.index', compact(
            'summary', 
            'trendData', 
            'doughnutData', 
            'barChartData', 
            'topServices',
            'period'
        ));
    }
    
    private function getSummaryData($period)
    {
        $endDate = now();
        $startDate = now()->subDays($period);
        
        // Total penghasilan
        $totalPenghasilan = Penghasilan::whereBetween('tanggal', [$startDate, $endDate])->sum('total');
        
        // Total pengeluaran
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->sum('nominal');
        
        // Laba bersih
        $labaBersih = $totalPenghasilan - $totalPengeluaran;
        
        // Margin profit
        $marginProfit = $totalPenghasilan > 0 ? ($labaBersih / $totalPenghasilan) * 100 : 0;
        
        // Persentase perubahan dari periode sebelumnya
        $previousStartDate = now()->subDays($period * 2);
        $previousEndDate = now()->subDays($period);
        
        $prevPenghasilan = Penghasilan::whereBetween('tanggal', [$previousStartDate, $previousEndDate])->sum('total');
        $prevPengeluaran = Pengeluaran::whereBetween('tanggal', [$previousStartDate, $previousEndDate])->sum('nominal');
        $prevLaba = $prevPenghasilan - $prevPengeluaran;
        
        $penghasilanChange = $prevPenghasilan > 0 ? (($totalPenghasilan - $prevPenghasilan) / $prevPenghasilan) * 100 : 0;
        $pengeluaranChange = $prevPengeluaran > 0 ? (($totalPengeluaran - $prevPengeluaran) / $prevPengeluaran) * 100 : 0;
        $labaChange = $prevLaba > 0 ? (($labaBersih - $prevLaba) / $prevLaba) * 100 : 0;
        
        return [
            'total_penghasilan' => $totalPenghasilan,
            'total_pengeluaran' => $totalPengeluaran,
            'laba_bersih' => $labaBersih,
            'margin_profit' => $marginProfit,
            'penghasilan_change' => $penghasilanChange,
            'pengeluaran_change' => $pengeluaranChange,
            'laba_change' => $labaChange,
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
            
            $incomeData[] = round($income / 1000); // dalam ribuan
            $expenseData[] = round($expense / 1000);
        }
        
        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
    
    private function getDoughnutData($period)
    {
        $startDate = now()->subDays($period);
        
        $pengeluaranByKategori = Pengeluaran::whereBetween('tanggal', [$startDate, now()])
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();
        
        $total = $pengeluaranByKategori->sum('total');
        
        $labels = [];
        $data = [];
        $colors = [
            'Stok Barang' => '#B33232',
            'Operasional' => '#F97316',
            'Peralatan' => '#EAB308',
            'Lainnya' => '#64748b',
        ];
        
        foreach ($pengeluaranByKategori as $item) {
            $labels[] = $item->kategori;
            $percentage = $total > 0 ? round(($item->total / $total) * 100) : 0;
            $data[] = $percentage;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_values($colors),
        ];
    }
    
    private function getBarChartData()
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $labels[] = $month->format('M');
            
            $income = Penghasilan::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('total');
            $expense = Pengeluaran::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('nominal');
            
            $incomeData[] = round($income / 1000);
            $expenseData[] = round($expense / 1000);
        }
        
        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
    
    private function getTopServices($period)
    {
        $startDate = now()->subDays($period);
        
        // Group by service type (ini contoh, sesuaikan dengan struktur data Anda)
        $services = Penghasilan::whereBetween('tanggal', [$startDate, now()])
            ->select('service', DB::raw('COUNT(*) as total'))
            ->groupBy('service')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();
        
        $maxTotal = $services->max('total') ?: 1;
        
        $result = [];
        $colors = ['bg-brand-income', 'bg-brand-income/80', 'bg-brand-primary', 'bg-brand-primary/80', 'bg-orange-500', 'bg-yellow-500'];
        
        foreach ($services as $index => $service) {
            $percentage = round(($service->total / $maxTotal) * 100);
            $width = "w-[{$percentage}%]";
            
            $result[] = [
                'name' => $service->service,
                'count' => $service->total,
                'width' => $width,
                'color' => $colors[$index] ?? 'bg-gray-500',
            ];
        }
        
        return $result;
    }
    
    public function exportPDF(Request $request)
    {
        $period = $request->get('period', 30);
        
        $summary = $this->getSummaryData($period);
        $trendData = $this->getTrendData($period);
        $doughnutData = $this->getDoughnutData($period);
        $barChartData = $this->getBarChartData();
        $topServices = $this->getTopServices($period);
        
        $data = [
            'summary' => $summary,
            'trendData' => $trendData,
            'doughnutData' => $doughnutData,
            'barChartData' => $barChartData,
            'topServices' => $topServices,
            'period' => $period,
            'exportDate' => now()->format('d F Y H:i:s'),
        ];
        
        $pdf = Pdf::loadView('admin.statistik.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-statistik-' . date('Y-m-d-His') . '.pdf');
    }
}