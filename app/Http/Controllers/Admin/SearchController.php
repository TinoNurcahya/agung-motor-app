<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        // Search di Penghasilan
        $penghasilan = Penghasilan::where('plat_nomor', 'like', "%{$query}%")
            ->orWhere('nama_pemilik', 'like', "%{$query}%")
            ->orWhere('service', 'like', "%{$query}%")
            ->orWhere('sparepart', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'penghasilan',
                    'type_label' => 'Penghasilan',
                    'title' => $item->plat_nomor . ' - ' . $item->nama_pemilik,
                    'subtitle' => $item->service,
                    'value' => 'Rp ' . number_format($item->total, 0, ',', '.'),
                    'url' => route('admin.penghasilan.edit', $item->id),
                    'date' => $item->tanggal->format('d/m/Y'),
                    'icon' => 'fa-money-bill-wave',
                    'icon_color' => 'text-brand-income'
                ];
            });
        
        // Search di Pengeluaran
        $pengeluaran = Pengeluaran::where('keterangan', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'pengeluaran',
                    'type_label' => 'Pengeluaran',
                    'title' => $item->keterangan,
                    'subtitle' => $item->kategori,
                    'value' => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                    'url' => route('admin.pengeluaran.edit', $item->id),
                    'date' => $item->tanggal->format('d/m/Y'),
                    'icon' => 'fa-receipt',
                    'icon_color' => 'text-brand-expense'
                ];
            });
        
        // Search di Produk
        $produk = Produk::where('nama', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'produk',
                    'type_label' => 'Produk',
                    'title' => $item->nama,
                    'subtitle' => $item->kategori . ' | SKU: ' . ($item->sku ?: '-'),
                    'value' => 'Stok: ' . $item->stok . ' pcs',
                    'url' => route('admin.produk.edit', $item->id),
                    'date' => null,
                    'icon' => 'fa-box',
                    'icon_color' => 'text-cyan-500'
                ];
            });
        
        // Search Menu & Pages (Quick Access)
        $menuItems = $this->searchMenuItems($query);
        
        // Search Statistics Data
        $statistics = $this->searchStatistics($query);
        
        // Search AI Insights (Rekomendasi berdasarkan data)
        $aiInsights = $this->searchAIInsights($query);
        
        // Search Users (jika ada user selain admin)
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->where('id', '!=', Auth::id())
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'user',
                    'type_label' => 'Pengguna',
                    'title' => $item->name,
                    'subtitle' => $item->email,
                    'value' => ucfirst($item->role),
                    'url' => '#', // Bisa ditambahkan route edit user jika ada
                    'date' => $item->created_at->format('d/m/Y'),
                    'icon' => 'fa-user',
                    'icon_color' => 'text-purple-500'
                ];
            });
        
        // Gabungkan semua hasil
        $results = $penghasilan->concat($pengeluaran)->concat($produk)->concat($menuItems)->concat($statistics)->concat($aiInsights)->concat($users);
        
        // Urutkan berdasarkan relevansi
        $results = $results->sortByDesc(function($item) use ($query) {
            $score = 0;
            if (stripos($item['title'], $query) !== false) $score += 10;
            if (isset($item['subtitle']) && stripos($item['subtitle'], $query) !== false) $score += 5;
            return $score;
        });
        
        // Kelompokkan berdasarkan tipe
        $grouped = [
            'menu' => $results->where('type', 'menu')->values(),
            'penghasilan' => $results->where('type', 'penghasilan')->values(),
            'pengeluaran' => $results->where('type', 'pengeluaran')->values(),
            'produk' => $results->where('type', 'produk')->values(),
            'statistik' => $results->where('type', 'statistik')->values(),
            'ai_insight' => $results->where('type', 'ai_insight')->values(),
            'user' => $results->where('type', 'user')->values(),
        ];
        
        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => $results->count(),
            'grouped' => $grouped,
            'all' => $results->values()
        ]);
    }
    
    private function searchMenuItems($query)
    {
        $menuItems = [];
        
        // Daftar menu yang bisa dicari
        $menus = [
            ['Dashboard', 'admin.index', 'fa-gauge', 'Halaman utama dashboard admin'],
            ['Penghasilan', 'admin.penghasilan.index', 'fa-money-bill-wave', 'Kelola data penghasilan bengkel'],
            ['Tambah Penghasilan', 'admin.penghasilan.create', 'fa-plus-circle', 'Input transaksi penghasilan baru'],
            ['Pengeluaran', 'admin.pengeluaran.index', 'fa-receipt', 'Kelola data pengeluaran operasional'],
            ['Tambah Pengeluaran', 'admin.pengeluaran.create', 'fa-plus-circle', 'Input transaksi pengeluaran baru'],
            ['Produk', 'admin.produk.index', 'fa-box', 'Kelola stok dan harga produk'],
            ['Tambah Produk', 'admin.produk.create', 'fa-plus-circle', 'Input produk baru'],
            ['Statistik', 'admin.statistik', 'fa-chart-line', 'Visualisasi data keuangan'],
            ['AI Analitik', 'admin.ai', 'fa-robot', 'Prediksi dan analisis cerdas'],
            ['Profile', 'profile.edit', 'fa-user', 'Edit profil pengguna'],
        ];
        
        $queryLower = strtolower($query);
        
        foreach ($menus as $menu) {
            $matchScore = 0;
            
            // Cek nama menu
            if (stripos($menu[0], $query) !== false) {
                $matchScore += 10;
            }
            // Cek deskripsi
            if (stripos($menu[3], $query) !== false) {
                $matchScore += 5;
            }
            
            if ($matchScore > 0) {
                $menuItems[] = [
                    'id' => null,
                    'type' => 'menu',
                    'type_label' => 'Menu',
                    'title' => $menu[0],
                    'subtitle' => $menu[3],
                    'value' => 'Akses Cepat',
                    'url' => route($menu[1]),
                    'date' => null,
                    'icon' => $menu[2],
                    'icon_color' => 'text-brand-primary',
                    'score' => $matchScore
                ];
            }
        }
        
        return collect($menuItems);
    }
    
    private function searchStatistics($query)
    {
        $statistics = [];
        $queryLower = strtolower($query);
        
        // Kata kunci terkait statistik
        $keywords = [
            'total penghasilan' => ['Total pendapatan keseluruhan', 'penghasilan'],
            'total pengeluaran' => ['Total biaya operasional', 'pengeluaran'],
            'laba bersih' => ['Keuntungan setelah dikurangi pengeluaran', 'keuntungan'],
            'margin profit' => ['Persentase keuntungan', 'profit'],
            'stok menipis' => ['Produk dengan stok rendah', 'stok'],
            'produk terlaris' => ['Produk dengan penjualan tertinggi', 'penjualan'],
            'pelanggan' => ['Data pelanggan bengkel', 'customer'],
            'grafik tren' => ['Visualisasi tren keuangan', 'chart'],
        ];
        
        foreach ($keywords as $key => $info) {
            if (stripos($key, $query) !== false || stripos($info[0], $query) !== false) {
                $statistics[] = [
                    'id' => null,
                    'type' => 'statistik',
                    'type_label' => 'Statistik',
                    'title' => ucfirst($key),
                    'subtitle' => $info[0],
                    'value' => 'Lihat di halaman Statistik',
                    'url' => route('admin.statistik'),
                    'date' => null,
                    'icon' => 'fa-chart-pie',
                    'icon_color' => 'text-green-500'
                ];
                break;
            }
        }
        
        return collect($statistics);
    }
    
    private function searchAIInsights($query)
    {
        $insights = [];
        $queryLower = strtolower($query);
        
        // Dapatkan data real dari database
        $totalPenghasilan = Penghasilan::sum('total');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $totalProduk = Produk::count();
        $produkHabis = Produk::where('stok', '<=', 0)->count();
        $produkMenipis = Produk::where('stok', '>', 0)->where('stok', '<', 10)->count();
        
        $aiTopics = [
            'prediksi omset' => [
                'deskripsi' => 'Prediksi pendapatan bulan depan berdasarkan data historis',
                'value' => 'Rp ' . number_format($totalPenghasilan * 0.15, 0, ',', '.'),
                'url' => route('admin.ai')
            ],
            'rekomendasi restok' => [
                'deskripsi' => 'Produk yang perlu segera di-restok',
                'value' => $produkHabis + $produkMenipis . ' item',
                'url' => route('admin.ai')
            ],
            'analisis pelanggan' => [
                'deskripsi' => 'Prediksi loyalitas pelanggan',
                'value' => '85.2% retensi',
                'url' => route('admin.ai')
            ],
            'strategi ai' => [
                'deskripsi' => 'Saran strategi dari AI untuk meningkatkan omset',
                'value' => 'Siapkan stok ekstra 15%',
                'url' => route('admin.ai')
            ],
        ];
        
        foreach ($aiTopics as $key => $info) {
            if (stripos($key, $query) !== false || stripos($info['deskripsi'], $query) !== false) {
                $insights[] = [
                    'id' => null,
                    'type' => 'ai_insight',
                    'type_label' => 'AI Insight',
                    'title' => ucfirst($key),
                    'subtitle' => $info['deskripsi'],
                    'value' => $info['value'],
                    'url' => $info['url'],
                    'date' => null,
                    'icon' => 'fa-microchip',
                    'icon_color' => 'text-cyan-500'
                ];
                break;
            }
        }
        
        // Tambahkan insight berdasarkan data real
        if (stripos('stok habis', $query) !== false || stripos('produk habis', $query) !== false) {
            $insights[] = [
                'id' => null,
                'type' => 'ai_insight',
                'type_label' => 'AI Insight',
                'title' => 'Stok Habis',
                'subtitle' => 'Produk dengan stok 0',
                'value' => $produkHabis . ' produk',
                'url' => route('admin.produk.index', ['search' => 'stok:0']),
                'date' => null,
                'icon' => 'fa-exclamation-triangle',
                'icon_color' => 'text-red-500'
            ];
        }
        
        if (stripos('stok menipis', $query) !== false) {
            $insights[] = [
                'id' => null,
                'type' => 'ai_insight',
                'type_label' => 'AI Insight',
                'title' => 'Stok Menipis',
                'subtitle' => 'Produk dengan stok kurang dari 10',
                'value' => $produkMenipis . ' produk',
                'url' => route('admin.produk.index', ['search' => 'stok:<10']),
                'date' => null,
                'icon' => 'fa-exclamation-triangle',
                'icon_color' => 'text-orange-500'
            ];
        }
        
        return collect($insights);
    }
    
    // Halaman hasil pencarian lengkap
    public function searchPage(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return redirect()->route('admin.index');
        }
        
        // Cari di semua tabel
        $penghasilan = Penghasilan::where('plat_nomor', 'like', "%{$query}%")
            ->orWhere('nama_pemilik', 'like', "%{$query}%")
            ->orWhere('service', 'like', "%{$query}%")
            ->orWhere('sparepart', 'like', "%{$query}%")
            ->orderBy('tanggal', 'desc')
            ->paginate(10, ['*'], 'penghasilan_page');
        
        $pengeluaran = Pengeluaran::where('keterangan', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->orderBy('tanggal', 'desc')
            ->paginate(10, ['*'], 'pengeluaran_page');
        
        $produk = Produk::where('nama', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->orderBy('nama')
            ->paginate(10, ['*'], 'produk_page');
        
        // Menu yang cocok dengan pencarian
        $matchedMenus = $this->getMatchedMenus($query);
        
        // Statistik pencarian
        $stats = [
            'total_penghasilan' => Penghasilan::where('plat_nomor', 'like', "%{$query}%")
                ->orWhere('nama_pemilik', 'like', "%{$query}%")
                ->orWhere('service', 'like', "%{$query}%")
                ->count(),
            'total_pengeluaran' => Pengeluaran::where('keterangan', 'like', "%{$query}%")
                ->orWhere('kategori', 'like', "%{$query}%")
                ->count(),
            'total_produk' => Produk::where('nama', 'like', "%{$query}%")
                ->orWhere('kategori', 'like', "%{$query}%")
                ->orWhere('sku', 'like', "%{$query}%")
                ->count(),
        ];
        
        // Data untuk chart (selalu tampilkan jika ada kata kunci terkait statistik)
        $showStatistik = $this->shouldShowStatistik($query);
        $chartData = null;
        $summaryData = null;
        
        if ($showStatistik) {
            $chartData = $this->getSearchChartData();
            $summaryData = $this->getSearchSummaryData();
        }
        
        // AI Insights - Selalu tampilkan rekomendasi berdasarkan pencarian
        $aiRecommendations = $this->getAIRecommendations($query);
        
        // AI Insights tambahan dari data real
        $aiInsights = $this->getSearchAIInsights($query);
        
        return view('admin.search.index', compact(
            'query', 
            'penghasilan', 
            'pengeluaran', 
            'produk', 
            'matchedMenus',
            'stats', 
            'showStatistik',
            'chartData',
            'summaryData',
            'aiRecommendations',
            'aiInsights'
        ));
    }

    private function shouldShowStatistik($query)
    {
        $keywords = ['statistik', 'grafik', 'chart', 'keuangan', 'pendapatan', 'pengeluaran', 'laba', 'profit', 'total', 'omset', 'trend'];
        $queryLower = strtolower($query);
        
        foreach ($keywords as $keyword) {
            if (stripos($queryLower, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    private function getSearchSummaryData()
    {
        $totalPenghasilan = Penghasilan::sum('total');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $labaBersih = $totalPenghasilan - $totalPengeluaran;
        $marginProfit = $totalPenghasilan > 0 ? ($labaBersih / $totalPenghasilan) * 100 : 0;
        
        // Data per bulan
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $income = Penghasilan::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('total');
            $expense = Pengeluaran::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('nominal');
            
            $monthlyData[] = [
                'month' => $month->translatedFormat('M Y'),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense
            ];
        }
        
        // Top produk berdasarkan stok menipis
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();
        
        return [
            'total_penghasilan' => $totalPenghasilan,
            'total_pengeluaran' => $totalPengeluaran,
            'laba_bersih' => $labaBersih,
            'margin_profit' => $marginProfit,
            'monthly_data' => $monthlyData,
            'low_stock_products' => $lowStockProducts
        ];
    }

    private function getSearchAIInsights($query)
    {
        $insights = [];
        $queryLower = strtolower($query);
        
        // Data real dari database
        $totalPenghasilan = Penghasilan::sum('total');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $totalProduk = Produk::count();
        $produkHabis = Produk::where('stok', '<=', 0)->count();
        $produkMenipis = Produk::where('stok', '>', 0)->where('stok', '<', 10)->count();
        $totalTransaksi = Penghasilan::count();
        
        // Insight berdasarkan kata kunci
        if (stripos($queryLower, 'stok') !== false || stripos($queryLower, 'produk') !== false) {
            if ($produkHabis > 0) {
                $insights[] = [
                    'title' => '⚠️ Peringatan Stok Habis',
                    'message' => "Terdapat {$produkHabis} produk dengan stok habis. Segera lakukan restok!",
                    'url' => route('admin.produk.index', ['search' => 'stok:0']),
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'text-red-500',
                    'bg' => 'bg-red-500/10'
                ];
            }
            
            if ($produkMenipis > 0) {
                $insights[] = [
                    'title' => '📦 Stok Menipis',
                    'message' => "{$produkMenipis} produk memiliki stok kurang dari 10. Prioritaskan restok!",
                    'url' => route('admin.produk.index', ['search' => 'stok:<10']),
                    'icon' => 'fa-box',
                    'color' => 'text-orange-500',
                    'bg' => 'bg-orange-500/10'
                ];
            }
        }
        
        if (stripos($queryLower, 'keuangan') !== false || stripos($queryLower, 'laba') !== false || stripos($queryLower, 'profit') !== false) {
            $laba = $totalPenghasilan - $totalPengeluaran;
            $margin = $totalPenghasilan > 0 ? ($laba / $totalPenghasilan) * 100 : 0;
            
            $insights[] = [
                'title' => '💰 Ringkasan Keuangan',
                'message' => "Total Pendapatan: Rp " . number_format($totalPenghasilan, 0, ',', '.') . 
                            " | Total Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') .
                            " | Laba Bersih: Rp " . number_format($laba, 0, ',', '.') .
                            " | Margin Profit: " . number_format($margin, 1) . "%",
                'url' => route('admin.statistik'),
                'icon' => 'fa-chart-line',
                'color' => 'text-green-500',
                'bg' => 'bg-green-500/10'
            ];
        }
        
        if (stripos($queryLower, 'oli') !== false) {
            $oliProduk = Produk::where('nama', 'like', '%oli%')->get();
            if ($oliProduk->count() > 0) {
                $totalStokOli = $oliProduk->sum('stok');
                $insights[] = [
                    'title' => '🛢️ Analisis Produk Oli',
                    'message' => "Terdapat {$oliProduk->count()} produk oli dengan total stok {$totalStokOli} pcs. " .
                                "Rekomendasi: Siapkan stok ekstra untuk oli yang paling laris.",
                    'url' => route('admin.produk.index', ['search' => 'oli']),
                    'icon' => 'fa-oil-can',
                    'color' => 'text-cyan-500',
                    'bg' => 'bg-cyan-500/10'
                ];
            }
        }
        
        if (stripos($queryLower, 'ban') !== false) {
            $banProduk = Produk::where('nama', 'like', '%ban%')->get();
            if ($banProduk->count() > 0) {
                $insights[] = [
                    'title' => ' Analisis Produk Ban',
                    'message' => "Terdapat {$banProduk->count()} produk ban. Cek stok dan harga kompetitor untuk optimasi penjualan.",
                    'url' => route('admin.produk.index', ['search' => 'ban']),
                    'icon' => 'fa-tire',
                    'color' => 'text-purple-500',
                    'bg' => 'bg-purple-500/10'
                ];
            }
        }
        
        if (stripos($queryLower, 'servis') !== false || stripos($queryLower, 'service') !== false) {
            $topService = Penghasilan::select('service', DB::raw('count(*) as total'))
                ->groupBy('service')
                ->orderBy('total', 'desc')
                ->first();
            
            if ($topService) {
                $insights[] = [
                    'title' => '🔧 Layanan Terpopuler',
                    'message' => "Layanan '{$topService->service}' menjadi yang paling banyak diminati dengan {$topService->total} transaksi.",
                    'url' => route('admin.penghasilan.index'),
                    'icon' => 'fa-wrench',
                    'color' => 'text-blue-500',
                    'bg' => 'bg-blue-500/10'
                ];
            }
        }
        
        // Insight umum jika tidak ada yang spesifik
        if (empty($insights)) {
            $insights[] = [
                'title' => '🤖 AI Insight',
                'message' => "Berdasarkan data, total transaksi tercatat {$totalTransaksi} kali. " .
                            "Tren positif terlihat pada layanan servis rutin dan penjualan sparepart.",
                'url' => route('admin.ai'),
                'icon' => 'fa-microchip',
                'color' => 'text-cyan-500',
                'bg' => 'bg-cyan-500/10'
            ];
        }
        
        return $insights;
    }

    private function getMatchedMenus($query)
    {
        $menus = [
            ['Dashboard', 'admin.index', 'fa-gauge', 'Halaman utama dashboard admin', 'ringkasan data keuangan'],
            ['Penghasilan', 'admin.penghasilan.index', 'fa-money-bill-wave', 'Kelola data penghasilan bengkel', 'pendapatan pemasukan'],
            ['Tambah Penghasilan', 'admin.penghasilan.create', 'fa-plus-circle', 'Input transaksi penghasilan baru', 'tambah pemasukan'],
            ['Pengeluaran', 'admin.pengeluaran.index', 'fa-receipt', 'Kelola data pengeluaran operasional', 'biaya operasional'],
            ['Tambah Pengeluaran', 'admin.pengeluaran.create', 'fa-plus-circle', 'Input transaksi pengeluaran baru', 'tambah biaya'],
            ['Produk', 'admin.produk.index', 'fa-box', 'Kelola stok dan harga produk', 'barang sparepart'],
            ['Tambah Produk', 'admin.produk.create', 'fa-plus-circle', 'Input produk baru', 'tambah barang'],
            ['Statistik', 'admin.statistik', 'fa-chart-line', 'Visualisasi data keuangan', 'grafik laporan'],
            ['AI Analitik', 'admin.ai', 'fa-robot', 'Prediksi dan analisis cerdas', 'kecerdasan buatan'],
            ['Profile', 'profile.edit', 'fa-user', 'Edit profil pengguna', 'akun saya'],
        ];
        
        $matched = [];
        $queryLower = strtolower($query);
        
        foreach ($menus as $menu) {
            $matchScore = 0;
            $matchedKeywords = [];
            
            // Cek nama menu
            if (stripos($menu[0], $query) !== false) {
                $matchScore += 10;
                $matchedKeywords[] = $menu[0];
            }
            // Cek deskripsi
            if (stripos($menu[3], $query) !== false) {
                $matchScore += 5;
                $matchedKeywords[] = $menu[3];
            }
            // Cek keywords tambahan
            if (stripos($menu[4], $query) !== false) {
                $matchScore += 8;
                $matchedKeywords[] = $menu[4];
            }
            
            if ($matchScore > 0) {
                $matched[] = [
                    'title' => $menu[0],
                    'description' => $menu[3],
                    'url' => route($menu[1]),
                    'icon' => $menu[2],
                    'match_score' => $matchScore,
                    'matched_keywords' => $matchedKeywords
                ];
            }
        }
        
        // Urutkan berdasarkan skor tertinggi
        usort($matched, function($a, $b) {
            return $b['match_score'] - $a['match_score'];
        });
        
        return $matched;
    }

    private function getSearchChartData()
    {
        $period = 30;
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
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
    
    private function getAIRecommendations($query)
    {
        $recommendations = [];
        
        // Rekomendasi berdasarkan kata kunci
        if (stripos($query, 'oli') !== false) {
            $oliProduk = Produk::where('nama', 'like', '%oli%')->limit(3)->get();
            foreach ($oliProduk as $produk) {
                $recommendations[] = [
                    'title' => 'Stok Oli Menipis',
                    'message' => "Produk {$produk->nama} tersisa {$produk->stok} pcs. Segera lakukan restok!",
                    'url' => route('admin.produk.edit', $produk->id),
                    'icon' => 'fa-oil-can'
                ];
            }
        }
        
        if (stripos($query, 'ban') !== false) {
            $banProduk = Produk::where('nama', 'like', '%ban%')->limit(3)->get();
            foreach ($banProduk as $produk) {
                $recommendations[] = [
                    'title' => 'Rekomendasi Restok Ban',
                    'message' => "Produk {$produk->nama} stok {$produk->stok} pcs. Permintaan tinggi bulan ini.",
                    'url' => route('admin.produk.edit', $produk->id),
                    'icon' => 'fa-tire'
                ];
            }
        }
        
        if (stripos($query, 'servis') !== false || stripos($query, 'service') !== false) {
            $topService = Penghasilan::select('service', DB::raw('count(*) as total'))
                ->groupBy('service')
                ->orderBy('total', 'desc')
                ->first();
            
            if ($topService) {
                $recommendations[] = [
                    'title' => 'Layanan Terpopuler',
                    'message' => "Layanan '{$topService->service}' adalah yang paling banyak diminati ({$topService->total} kali).",
                    'url' => route('admin.penghasilan.index'),
                    'icon' => 'fa-wrench'
                ];
            }
        }
        
        if (stripos($query, 'keuntungan') !== false || stripos($query, 'profit') !== false) {
            $total = Penghasilan::sum('total') - Pengeluaran::sum('nominal');
            $recommendations[] = [
                'title' => 'Analisis Keuntungan',
                'message' => "Total keuntungan saat ini: Rp " . number_format($total, 0, ',', '.'),
                'url' => route('admin.statistik'),
                'icon' => 'fa-chart-line'
            ];
        }
        
        return $recommendations;
    }
}