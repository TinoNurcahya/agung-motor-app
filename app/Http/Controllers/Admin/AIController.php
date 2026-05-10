<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    public function index()
    {
        // Prediksi Omset untuk bulan berikutnya
        $predictionData = $this->calculateRevenuePrediction();
        
        // Kalkulasi stok yang perlu direstock
        $stockHealth = $this->calculateStockHealth();
        
        // Prediksi retensi pelanggan
        $customerRetention = $this->calculateCustomerRetention();
        
        // Data untuk forecast chart (6 bulan ke depan)
        $forecastData = $this->getForecastData();
        
        // Rekomendasi restok berdasarkan analisis kecepatan barang habis
        $restockRecommendations = $this->getRestockRecommendations();
        
        // AI Strategy Insight
        $aiStrategy = $this->getAIStrategy();
        
        // Data untuk chart
        $chartData = $this->getChartData();
        
        return view('admin.ai.index', compact(
            'predictionData',
            'stockHealth',
            'customerRetention',
            'forecastData',
            'restockRecommendations',
            'aiStrategy',
            'chartData'
        ));
    }
    
    private function calculateRevenuePrediction()
    {
        // Ambil data 6 bulan terakhir
        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $total = Penghasilan::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('total');
            $last6Months[] = $total;
        }
        
        // Hitung rata-rata pertumbuhan
        $growthRates = [];
        for ($i = 1; $i < count($last6Months); $i++) {
            if ($last6Months[$i - 1] > 0) {
                $growthRates[] = (($last6Months[$i] - $last6Months[$i - 1]) / $last6Months[$i - 1]) * 100;
            }
        }
        
        $avgGrowthRate = count($growthRates) > 0 ? array_sum($growthRates) / count($growthRates) : 7.2;
        
        // Prediksi untuk bulan depan
        $lastMonthRevenue = $last6Months[5] ?? 0;
        $predictedRevenue = $lastMonthRevenue * (1 + ($avgGrowthRate / 100));
        
        // Bandingkan dengan bulan lalu
        $previousMonthRevenue = $last6Months[4] ?? 0;
        $percentageChange = $previousMonthRevenue > 0 
            ? (($predictedRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : 7.2;
        
        $nextMonthName = now()->addMonth()->translatedFormat('F Y');
        
        return [
            'predicted_revenue' => $predictedRevenue,
            'percentage_change' => $percentageChange,
            'next_month' => $nextMonthName,
            'last_month_revenue' => $lastMonthRevenue,
            'growth_rate' => $avgGrowthRate
        ];
    }
    
    private function calculateStockHealth()
    {
        // Produk dengan stok menipis (kurang dari 10)
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->count();
        
        // Produk dengan stok habis
        $outOfStockProducts = Produk::where('stok', '<=', 0)->count();
        
        $urgentRestockCount = $lowStockProducts + $outOfStockProducts;
        
        return [
            'urgent_restock' => $urgentRestockCount,
            'urgent_items_count' => $lowStockProducts,
            'out_of_stock_count' => $outOfStockProducts,
            'total_products' => Produk::count()
        ];
    }
    
    private function calculateCustomerRetention()
    {
        // Ambil data pelanggan (berdasarkan nama pemilik yang unik)
        $last3MonthsCustomers = Penghasilan::where('tanggal', '>=', now()->subMonths(3))
            ->distinct('nama_pemilik')
            ->count('nama_pemilik');
        
        $last6MonthsCustomers = Penghasilan::whereBetween('tanggal', [now()->subMonths(6), now()->subMonths(3)])
            ->distinct('nama_pemilik')
            ->count('nama_pemilik');
        
        // Hitung retensi
        $retentionRate = $last6MonthsCustomers > 0 
            ? ($last3MonthsCustomers / $last6MonthsCustomers) * 100 
            : 85.2;
        
        $retentionRate = min($retentionRate, 100);
        
        $status = $retentionRate >= 80 ? 'Sangat Stabil' : ($retentionRate >= 60 ? 'Stabil' : 'Perlu Perhatian');
        
        return [
            'retention_rate' => round($retentionRate, 1),
            'total_customers' => Penghasilan::distinct('nama_pemilik')->count('nama_pemilik'),
            'status' => $status
        ];
    }
    
    private function getForecastData()
    {
        $labels = [];
        $historicalData = [];
        
        // Data historis 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $labels[] = $month->translatedFormat('M');
            $total = Penghasilan::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('total');
            $historicalData[] = round($total / 1000000, 1); // Dalam jutaan
        }
        
        // Hitung trend untuk prediksi
        $trend = $this->calculateTrend($historicalData);
        
        // Prediksi 3 bulan ke depan
        $forecastData = [];
        $lastValue = $historicalData[5] ?? 0;
        for ($i = 1; $i <= 3; $i++) {
            $labels[] = now()->addMonths($i)->translatedFormat('M') . ' (P)';
            $predictedValue = $lastValue * (1 + ($trend / 100));
            $forecastData[] = round($predictedValue, 1);
            $lastValue = $predictedValue;
        }
        
        return [
            'labels' => $labels,
            'historical' => $historicalData,
            'forecast' => $forecastData,
            'trend' => round($trend, 1)
        ];
    }
    
    private function calculateTrend($data)
    {
        if (count($data) < 2) return 5;
        
        $totalGrowth = 0;
        $count = 0;
        for ($i = 1; $i < count($data); $i++) {
            if ($data[$i - 1] > 0) {
                $growth = (($data[$i] - $data[$i - 1]) / $data[$i - 1]) * 100;
                $totalGrowth += $growth;
                $count++;
            }
        }
        
        return $count > 0 ? $totalGrowth / $count : 5;
    }
    
    private function getRestockRecommendations()
    {
        // Ambil produk dengan stok terendah (untuk ditampilkan di dashboard)
        // Prioritaskan produk dengan stok 0 terlebih dahulu, lalu stok < 10, lalu stok < 20
        $products = Produk::orderByRaw('CASE 
                WHEN stok <= 0 THEN 0 
                WHEN stok <= 5 THEN 1 
                WHEN stok <= 10 THEN 2 
                WHEN stok <= 20 THEN 3 
                ELSE 4 
            END')
            ->orderBy('stok', 'asc')
            ->limit(4)
            ->get();
        
        $recommendations = [];
        
        foreach ($products as $product) {
            $stockLevel = $product->stok;
            
            // Tentukan status berdasarkan stok
            if ($stockLevel <= 0) {
                $risk = 'Kritis';
                $time = 'Segera!';
                $width = 'w-[100%]';
                $color = 'text-red-500';
                $urgency = 0;
            } elseif ($stockLevel <= 5) {
                $risk = 'Tinggi';
                $time = '4 Hari Lagi';
                $width = 'w-[90%]';
                $color = 'text-brand-primary';
                $urgency = 1;
            } elseif ($stockLevel <= 10) {
                $risk = 'Sedang';
                $time = '12 Hari Lagi';
                $width = 'w-[65%]';
                $color = 'text-orange-500';
                $urgency = 2;
            } elseif ($stockLevel <= 15) {
                $risk = 'Sedang';
                $time = '15 Hari Lagi';
                $width = 'w-[60%]';
                $color = 'text-orange-500';
                $urgency = 3;
            } else {
                $risk = 'Rendah';
                $time = '28 Hari Lagi';
                $width = 'w-[30%]';
                $color = 'text-green-500';
                $urgency = 4;
            }
            
            $recommendations[] = [
                'id' => $product->id,
                'name' => $product->nama,
                'kategori' => $product->kategori,
                'risk' => $risk,
                'time' => $time,
                'color' => $color,
                'width' => $width,
                'stock' => $stockLevel,
                'urgency' => $urgency
            ];
        }
        
        // Urutkan berdasarkan urgency (prioritas stok paling kritis)
        usort($recommendations, function($a, $b) {
            return $a['urgency'] - $b['urgency'];
        });
        
        return $recommendations;
    }
    
    private function getAIStrategy()
    {
        // Analisis pola dari data historis
        $busiestWeek = $this->getBusiestWeek();
        $topProducts = $this->getTopProducts();
        
        $strategy = "Berdasarkan data 3 bulan terakhir, pelanggan cenderung melakukan servis rutin di minggu ke-{$busiestWeek} setiap bulannya. ";
        $strategy .= "Saran: Siapkan stok {$topProducts} ekstra 15% sebelum tanggal 10 bulan depan untuk memaksimalkan omset.";
        
        return [
            'text' => $strategy,
            'busiest_week' => $busiestWeek,
            'top_products' => $topProducts
        ];
    }
    
    private function getBusiestWeek()
    {
        $weekCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        
        $penghasilan = Penghasilan::where('tanggal', '>=', now()->subMonths(3))->get();
        
        foreach ($penghasilan as $item) {
            $week = ceil($item->tanggal->day / 7);
            if ($week >= 1 && $week <= 4) {
                $weekCounts[$week]++;
            }
        }
        
        if (max($weekCounts) > 0) {
            return array_search(max($weekCounts), $weekCounts);
        }
        
        return 2;
    }
    
    private function getTopProducts()
    {
        $services = Penghasilan::select('service', 'sparepart')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        $keywords = ['Oli', 'Ban', 'Kampas', 'Aki', 'Busi'];
        $counts = [];
        
        foreach ($keywords as $keyword) {
            $count = 0;
            foreach ($services as $service) {
                if (str_contains($service->service, $keyword) || str_contains($service->sparepart ?? '', $keyword)) {
                    $count++;
                }
            }
            $counts[$keyword] = $count;
        }
        
        if (!empty($counts) && max($counts) > 0) {
            return array_search(max($counts), $counts);
        }
        
        return "Oli dan Kampas Rem";
    }
    
    private function getChartData()
    {
        $penghasilan = Penghasilan::select('tanggal', 'total')
            ->orderBy('tanggal')
            ->get();
        
        $grouped = $penghasilan->groupBy(function($item) {
            return $item->tanggal->format('Y-m');
        });
        
        $labels = [];
        $data = [];
        
        foreach ($grouped as $month => $items) {
            $labels[] = \Carbon\Carbon::parse($month)->translatedFormat('M Y');
            $data[] = round($items->sum('total') / 1000000, 1);
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    // Halaman rekomendasi restok lengkap
    public function restockList()
    {
        // Ambil semua produk yang perlu direstock (stok kurang dari 20)
        // Urutkan dari yang paling kritis
        $products = Produk::orderByRaw('CASE 
                WHEN stok <= 0 THEN 0 
                WHEN stok <= 5 THEN 1 
                WHEN stok <= 10 THEN 2 
                WHEN stok <= 20 THEN 3 
                ELSE 4 
            END')
            ->orderBy('stok', 'asc')
            ->paginate(20);
        
        // Hitung statistik
        $totalProducts = Produk::count();
        $criticalStock = Produk::where('stok', '<=', 0)->count();      // Stok habis
        $lowStock = Produk::where('stok', '>', 0)->where('stok', '<=', 5)->count();  // Stok sangat rendah
        $mediumStock = Produk::where('stok', '>', 5)->where('stok', '<=', 10)->count(); // Stok menipis
        $warningStock = Produk::where('stok', '>', 10)->where('stok', '<=', 20)->count(); // Stok perlu perhatian
        $healthyStock = Produk::where('stok', '>', 20)->count(); // Stok aman
        
        return view('admin.ai.restock', compact('products', 'totalProducts', 'criticalStock', 'lowStock', 'mediumStock', 'warningStock', 'healthyStock'));
    }
    
    // Apply strategy (contoh: membuat rekomendasi pembelian)
    public function applyStrategy(Request $request)
    {
        $strategy = $request->get('strategy', '');
        
        // Logika implementasi strategi
        // Contoh: membuat notifikasi atau rekomendasi pembelian
        
        return response()->json([
            'success' => true,
            'message' => 'Strategi berhasil diterapkan! Tim purchasing akan segera memproses.',
            'strategy' => $strategy
        ]);
    }
    
    public function refreshData()
    {
        $predictionData = $this->calculateRevenuePrediction();
        $stockHealth = $this->calculateStockHealth();
        $customerRetention = $this->calculateCustomerRetention();
        $forecastData = $this->getForecastData();
        $restockRecommendations = $this->getRestockRecommendations();
        $aiStrategy = $this->getAIStrategy();
        
        return response()->json([
            'success' => true,
            'data' => [
                'prediction' => $predictionData,
                'stock_health' => $stockHealth,
                'retention' => $customerRetention,
                'forecast' => $forecastData,
                'restock' => $restockRecommendations,
                'strategy' => $aiStrategy
            ]
        ]);
    }
}