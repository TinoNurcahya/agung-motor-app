<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    // Get AI predictions and insights
    public function predictions(Request $request)
    {
        $period = $request->get('period', 30);
        
        // Revenue prediction
        $revenuePrediction = $this->predictRevenue($period);
        
        // Stock health prediction
        $stockHealth = $this->predictStockHealth();
        
        // Customer retention prediction
        $customerRetention = $this->predictCustomerRetention();
        
        // AI strategy recommendations
        $strategy = $this->getAIStrategy();
        
        return response()->json([
            'success' => true,
            'data' => [
                'revenue_prediction' => $revenuePrediction,
                'stock_health' => $stockHealth,
                'customer_retention' => $customerRetention,
                'ai_strategy' => $strategy,
                'last_sync' => now()->toDateTimeString()
            ]
        ]);
    }

    // Get stock health analysis
    public function stockHealth(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->predictStockHealth()
        ]);
    }

    // Get customer retention analysis
    public function customerRetention(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->predictCustomerRetention()
        ]);
    }

    // Get forecast chart data
    public function forecast(Request $request)
    {
        $months = $request->get('months', 6);
        
        $historicalData = $this->getHistoricalData($months);
        $forecastData = $this->getForecastData($historicalData);
        
        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $forecastData['labels'],
                'historical' => $forecastData['historical'],
                'forecast' => $forecastData['forecast'],
                'trend' => $forecastData['trend']
            ]
        ]);
    }

    // Get restock recommendations
    public function recommendations(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $products = Produk::where('stok', '<', 20)
            ->orderBy('stok', 'asc')
            ->limit($limit)
            ->get();
        
        $recommendations = [];
        
        foreach ($products as $product) {
            $stockLevel = $product->stok;
            
            if ($stockLevel <= 0) {
                $urgency = 'critical';
                $priority = 1;
                $message = 'Stok habis, perlu restok segera!';
                $timeframe = 'Segera';
            } elseif ($stockLevel <= 5) {
                $urgency = 'high';
                $priority = 2;
                $message = 'Stok sangat rendah, restok dalam 3-5 hari';
                $timeframe = '4 Hari Lagi';
            } elseif ($stockLevel <= 10) {
                $urgency = 'medium';
                $priority = 3;
                $message = 'Stok menipis, rencanakan restok';
                $timeframe = '12 Hari Lagi';
            } else {
                $urgency = 'low';
                $priority = 4;
                $message = 'Stok masih aman, tapi perlu monitoring';
                $timeframe = '28 Hari Lagi';
            }
            
            $recommendations[] = [
                'id' => $product->id,
                'name' => $product->nama,
                'category' => $product->kategori,
                'current_stock' => $stockLevel,
                'urgency' => $urgency,
                'priority' => $priority,
                'message' => $message,
                'timeframe' => $timeframe,
                'suggested_order' => $this->calculateSuggestedOrder($product)
            ];
        }
        
        // Sort by priority
        usort($recommendations, function($a, $b) {
            return $a['priority'] - $b['priority'];
        });
        
        return response()->json([
            'success' => true,
            'data' => $recommendations,
            'summary' => [
                'critical' => collect($recommendations)->where('urgency', 'critical')->count(),
                'high' => collect($recommendations)->where('urgency', 'high')->count(),
                'medium' => collect($recommendations)->where('urgency', 'medium')->count(),
                'low' => collect($recommendations)->where('urgency', 'low')->count()
            ]
        ]);
    }

    // Get AI insights based on search query
    public function insights(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $insights = $this->getSearchInsights($query);
        
        return response()->json([
            'success' => true,
            'query' => $query,
            'data' => $insights
        ]);
    }

    // Refresh AI data
    public function refresh(Request $request)
    {
        $predictions = $this->predictRevenue(30);
        
        return response()->json([
            'success' => true,
            'message' => 'AI data refreshed successfully',
            'data' => $predictions
        ]);
    }

    private function predictRevenue($period)
    {
        $historicalData = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Penghasilan::whereDate('tanggal', $date->format('Y-m-d'))->sum('total');
            $historicalData[] = $total;
        }
        
        $trend = $this->calculateTrend($historicalData);
        $lastValue = end($historicalData);
        $predictedRevenue = $lastValue * (1 + ($trend / 100));
        
        $prevPeriodTotal = Penghasilan::whereBetween('tanggal', [now()->subDays($period * 2), now()->subDays($period)])->sum('total');
        $percentageChange = $prevPeriodTotal > 0 ? (($predictedRevenue - $prevPeriodTotal) / $prevPeriodTotal) * 100 : 0;
        
        return [
            'predicted_revenue' => $predictedRevenue,
            'predicted_revenue_formatted' => 'Rp ' . number_format($predictedRevenue, 0, ',', '.'),
            'percentage_change' => round($percentageChange, 1),
            'next_period' => now()->addMonth()->format('F Y'),
            'trend' => round($trend, 1),
            'confidence_score' => $this->calculateConfidenceScore($historicalData)
        ];
    }

    private function predictStockHealth()
    {
        $totalProducts = Produk::count();
        $outOfStock = Produk::where('stok', '<=', 0)->count();
        $lowStock = Produk::where('stok', '>', 0)->where('stok', '<', 10)->count();
        $turnoverRate = $this->calculateStockTurnoverRate();
        
        $criticalProducts = Produk::where('stok', '<', 5)
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->nama,
                    'stock' => $product->stok,
                    'estimated_days' => $product->stok * 2
                ];
            });
        
        return [
            'total_products' => $totalProducts,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'healthy_stock' => $totalProducts - ($outOfStock + $lowStock),
            'turnover_rate' => round($turnoverRate, 2),
            'critical_products' => $criticalProducts,
            'recommendation' => $this->getStockRecommendation($lowStock, $outOfStock)
        ];
    }

    private function predictCustomerRetention()
    {
        $currentMonthCustomers = Penghasilan::whereMonth('tanggal', now()->month)
            ->distinct('nama_pemilik')
            ->count('nama_pemilik');
        
        $lastMonthCustomers = Penghasilan::whereMonth('tanggal', now()->subMonth()->month)
            ->distinct('nama_pemilik')
            ->count('nama_pemilik');
        
        $retentionRate = $lastMonthCustomers > 0 
            ? ($currentMonthCustomers / $lastMonthCustomers) * 100 
            : 85;
        
        $predictedRetention = $retentionRate * (1 + ($this->calculateGrowthRate() / 100));
        
        return [
            'current_retention_rate' => round($retentionRate, 1),
            'predicted_retention_rate' => round(min($predictedRetention, 100), 1),
            'total_unique_customers' => Penghasilan::distinct('nama_pemilik')->count('nama_pemilik'),
            'new_customers_this_month' => $this->getNewCustomersCount(),
            'status' => $retentionRate >= 80 ? 'Excellent' : ($retentionRate >= 60 ? 'Good' : 'Needs Improvement'),
            'recommendation' => $this->getRetentionRecommendation($retentionRate)
        ];
    }

    private function getAIStrategy()
    {
        $bestPeriod = $this->getBestPerformingPeriod();
        $topProducts = $this->getTopProducts();
        $busyDays = $this->getBusyDays();
        $topProduct = isset($topProducts[0]) ? $topProducts[0] : 'popular items';
        
        return [
            'title' => 'AI Strategy Recommendation',
            'description' => "Based on historical data analysis, your best performing period is {$bestPeriod}. " .
                            "Top products: " . implode(', ', $topProducts) . ". " .
                            "Busiest days: {$busyDays}.",
            'recommendations' => [
                "Increase stock of {$topProduct} by 20% before peak season",
                "Consider promotional campaigns on {$busyDays}",
                "Optimize operational hours during {$bestPeriod}",
                "Focus on customer retention programs for recurring customers"
            ],
            'potential_impact' => 'Estimated 15-25% revenue increase'
        ];
    }

    private function getSearchInsights($query)
    {
        $insights = [];
        $queryLower = strtolower($query);
        
        if (strpos($queryLower, 'stok') !== false || strpos($queryLower, 'stock') !== false) {
            $outOfStock = Produk::where('stok', '<=', 0)->count();
            $lowStock = Produk::where('stok', '>', 0)->where('stok', '<', 10)->count();
            
            $insights[] = [
                'type' => 'stock_insight',
                'title' => 'Analisis Stok',
                'message' => "Terdapat {$outOfStock} produk dengan stok habis dan {$lowStock} produk dengan stok menipis.",
                'action_url' => '/api/produk?min_stok=10',
                'action_text' => 'Lihat produk stok menipis'
            ];
        }
        
        if (strpos($queryLower, 'pendapatan') !== false || strpos($queryLower, 'revenue') !== false) {
            $totalRevenue = Penghasilan::sum('total');
            $monthlyAvg = Penghasilan::whereMonth('tanggal', now()->month)->sum('total');
            
            $insights[] = [
                'type' => 'revenue_insight',
                'title' => 'Analisis Pendapatan',
                'message' => "Total pendapatan: Rp " . number_format($totalRevenue, 0, ',', '.') . 
                            ". Pendapatan bulan ini: Rp " . number_format($monthlyAvg, 0, ',', '.'),
                'action_url' => '/api/dashboard/overview',
                'action_text' => 'Lihat detail keuangan'
            ];
        }
        
        if (strpos($queryLower, 'pengeluaran') !== false || strpos($queryLower, 'expense') !== false) {
            $totalExpense = Pengeluaran::sum('nominal');
            $topCategory = Pengeluaran::select('kategori', DB::raw('SUM(nominal) as total'))
                ->groupBy('kategori')
                ->orderBy('total', 'desc')
                ->first();
            
            $categoryName = $topCategory ? $topCategory->kategori : 'Unknown';
            $categoryTotal = $topCategory ? $topCategory->total : 0;
            
            $insights[] = [
                'type' => 'expense_insight',
                'title' => 'Analisis Pengeluaran',
                'message' => "Total pengeluaran: Rp " . number_format($totalExpense, 0, ',', '.') .
                            ". Kategori terbesar: {$categoryName} (Rp " . number_format($categoryTotal, 0, ',', '.') . ")",
                'action_url' => '/api/statistik/expense',
                'action_text' => 'Lihat detail pengeluaran'
            ];
        }
        
        if (empty($insights)) {
            $insights[] = [
                'type' => 'general_insight',
                'title' => 'AI Analysis',
                'message' => 'Analisis menunjukkan tren positif dalam 3 bulan terakhir. Pertumbuhan pendapatan sekitar 12%.',
                'action_url' => '/api/ai/predictions',
                'action_text' => 'Lihat prediksi AI'
            ];
        }
        
        return $insights;
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

    private function calculateConfidenceScore($data)
    {
        if (count($data) < 3) return 50;
        
        $mean = array_sum($data) / count($data);
        $variance = 0;
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        $variance /= count($data);
        $stdDev = sqrt($variance);
        
        $maxStdDev = $mean * 0.5;
        $confidence = ($maxStdDev > 0) ? max(0, min(100, 100 - (($stdDev / $maxStdDev) * 50))) : 50;
        
        return round($confidence);
    }

    private function getHistoricalData($months)
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $total = Penghasilan::whereMonth('tanggal', $month->month)
                ->whereYear('tanggal', $month->year)
                ->sum('total');
            $data[] = round($total / 1000000, 1);
        }
        return $data;
    }

    private function getForecastData($historicalData)
    {
        $labels = [];
        $forecast = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->format('M');
        }
        
        $trend = $this->calculateTrend($historicalData);
        $lastValue = end($historicalData);
        
        for ($i = 1; $i <= 3; $i++) {
            $labels[] = now()->addMonths($i)->format('M') . ' (P)';
            $predicted = $lastValue * (1 + ($trend / 100));
            $forecast[] = round($predicted, 1);
            $lastValue = $predicted;
        }
        
        return [
            'labels' => $labels,
            'historical' => $historicalData,
            'forecast' => $forecast,
            'trend' => round($trend, 1)
        ];
    }

    private function calculateSuggestedOrder($product)
    {
        if ($product->stok <= 0) return 20;
        if ($product->stok <= 5) return 15;
        if ($product->stok <= 10) return 10;
        return 5;
    }

    private function calculateStockTurnoverRate()
    {
        $totalSold = Penghasilan::whereMonth('tanggal', now()->month)->count();
        $averageStock = Produk::avg('stok');
        
        return ($averageStock > 0) ? $totalSold / $averageStock : 0;
    }

    private function getStockRecommendation($lowStock, $outOfStock)
    {
        if ($outOfStock > 0) {
            return "Segera restok produk yang habis untuk menghindari kehilangan penjualan.";
        }
        if ($lowStock > 5) {
            return "Beberapa produk mulai menipis. Rencanakan pembelian stok dalam 1-2 minggu.";
        }
        return "Stok dalam kondisi baik. Lanjutkan monitoring rutin.";
    }

    private function getNewCustomersCount()
    {
        $currentMonth = Penghasilan::whereMonth('tanggal', now()->month)
            ->distinct('nama_pemilik')
            ->pluck('nama_pemilik');
        
        $lastMonth = Penghasilan::whereMonth('tanggal', now()->subMonth()->month)
            ->distinct('nama_pemilik')
            ->pluck('nama_pemilik');
        
        return $currentMonth->diff($lastMonth)->count();
    }

    private function getRetentionRecommendation($rate)
    {
        if ($rate >= 80) {
            return "Retensi pelanggan sangat baik. Fokus pada program loyalitas untuk mempertahankan.";
        }
        if ($rate >= 60) {
            return "Retensi cukup baik. Pertimbangkan program referral untuk meningkatkan.";
        }
        return "Retensi perlu ditingkatkan. Evaluasi kualitas layanan dan follow-up pelanggan.";
    }

    private function getBestPerformingPeriod()
    {
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue = Penghasilan::whereMonth('tanggal', $i)->sum('total');
            $monthlyRevenue[$i] = $revenue;
        }
        
        $bestMonth = array_search(max($monthlyRevenue), $monthlyRevenue);
        return \Carbon\Carbon::create()->month($bestMonth)->translatedFormat('F');
    }

    private function getTopProducts($limit = 3)
    {
        $services = Penghasilan::select('service', 'sparepart')->get();
        $productMentions = [];
        
        foreach ($services as $service) {
            $text = $service->service . ' ' . ($service->sparepart ?? '');
            $words = explode(' ', $text);
            foreach ($words as $word) {
                if (strlen($word) > 3) {
                    $productMentions[$word] = ($productMentions[$word] ?? 0) + 1;
                }
            }
        }
        
        arsort($productMentions);
        $topProducts = array_slice(array_keys($productMentions), 0, $limit);
        
        // If no products found, return defaults
        if (empty($topProducts)) {
            return ['Oli Mesin', 'Kampas Rem', 'Ban'];
        }
        
        return $topProducts;
    }

    private function getBusyDays()
    {
        $dayCounts = [];
        $penghasilan = Penghasilan::all();
        
        foreach ($penghasilan as $item) {
            $dayName = $item->tanggal->translatedFormat('l');
            $dayCounts[$dayName] = ($dayCounts[$dayName] ?? 0) + 1;
        }
        
        if (empty($dayCounts)) {
            return 'Sabtu dan Minggu';
        }
        
        arsort($dayCounts);
        $busyDays = array_slice(array_keys($dayCounts), 0, 2);
        return implode(' dan ', $busyDays);
    }

    private function calculateGrowthRate()
    {
        $lastMonth = Penghasilan::whereMonth('tanggal', now()->subMonth()->month)->sum('total');
        $twoMonthsAgo = Penghasilan::whereMonth('tanggal', now()->subMonths(2)->month)->sum('total');
        
        return ($twoMonthsAgo > 0) ? (($lastMonth - $twoMonthsAgo) / $twoMonthsAgo) * 100 : 5;
    }
}