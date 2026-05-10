@extends('layouts.admin')

@section('title', 'Statistik')

@section('content')
  <div class="space-y-8">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span> Statistik
      </p>
      <h1 class="text-2xl font-bold">Statistik Keuangan</h1>
      <p class="text-muted text-sm">Visualisasi data penghasilan dan pengeluaran bengkel.</p>
    </div>

    {{-- Header Section Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div></div> {{-- Spacer for flex --}}
      <div class="flex items-center gap-3 text-sm">
        <select id="periodFilter" onchange="updateCharts()"
          class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
          <option value="7">7 Hari Terakhir</option>
          <option value="30" selected>30 Hari Terakhir</option>
          <option value="90">3 Bulan Terakhir</option>
        </select>
        <button class="glass px-4 py-2 rounded-lg text-muted hover:text-brand-primary transition-colors text-xs">
          <i class="fa-solid fa-download mr-2"></i> Export PDF
        </button>
      </div>
    </div>

    {{-- Summary Strip --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Total Penghasilan</p>
        <p class="text-2xl font-bold text-brand-income">Rp 45.2M</p>
        <p class="text-xs text-brand-income mt-1"><i class="fa-solid fa-arrow-up text-[10px]"></i> +12.5%</p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Total Pengeluaran</p>
        <p class="text-2xl font-bold text-brand-expense">Rp 12.4M</p>
        <p class="text-xs text-brand-expense mt-1"><i class="fa-solid fa-arrow-down text-[10px]"></i> -5.2%</p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Laba Bersih</p>
        <p class="text-2xl font-bold">Rp 32.8M</p>
        <p class="text-xs text-brand-income mt-1"><i class="fa-solid fa-arrow-up text-[10px]"></i> +18.3%</p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Margin Profit</p>
        <p class="text-2xl font-bold">72.6<span class="text-lg text-muted">%</span></p>
        <p class="text-xs text-brand-income mt-1"><i class="fa-solid fa-arrow-up text-[10px]"></i> +3.1%</p>
      </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid lg:grid-cols-3 gap-6">

      {{-- Main Area Chart --}}
      <div class="glass-card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
          <h4 class="font-bold">Tren Keuangan</h4>
          <div class="flex gap-4 text-xs text-muted">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-income"></span>
              Penghasilan</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-expense"></span>
              Pengeluaran</span>
          </div>
        </div>
        <div class="h-72">
          <canvas id="trendChart"></canvas>
        </div>
      </div>

      {{-- Doughnut Chart --}}
      <div class="glass-card p-6">
        <h4 class="font-bold mb-6">Distribusi Pengeluaran</h4>
        <div class="h-56 flex items-center justify-center">
          <canvas id="doughnutChart"></canvas>
        </div>
        <div class="mt-6 space-y-3">
          @foreach ([['Stok Barang', '65%', 'bg-brand-expense'], ['Operasional', '20%', 'bg-orange-500'], ['Peralatan', '10%', 'bg-yellow-500'], ['Lainnya', '5%', 'bg-gray-500']] as [$label, $pct, $color])
            <div class="flex items-center justify-between text-sm">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>
                <span class="text-muted">{{ $label }}</span>
              </div>
              <span class="font-bold">{{ $pct }}</span>
            </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Bottom Charts Row --}}
    <div class="grid lg:grid-cols-2 gap-6">

      {{-- Bar Chart: Monthly Comparison --}}
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h4 class="font-bold">Perbandingan Bulanan</h4>
          <span class="text-xs text-muted">6 bulan terakhir</span>
        </div>
        <div class="h-64">
          <canvas id="barChart"></canvas>
        </div>
      </div>

      {{-- Top Services --}}
      <div class="glass-card p-6">
        <h4 class="font-bold mb-6">Layanan Terpopuler</h4>
        <div class="space-y-4">
          @foreach ([['Service Rutin', 156, 'w-[85%]', 'bg-brand-income'], ['Ganti Oli', 120, 'w-[70%]', 'bg-brand-income/80'], ['Overhaul Mesin', 45, 'w-[40%]', 'bg-brand-primary'], ['Ganti Ban', 38, 'w-[35%]', 'bg-brand-primary/80'], ['Servis Kelistrikan', 32, 'w-[30%]', 'bg-orange-500'], ['Cat & Restorasi', 18, 'w-[18%]', 'bg-yellow-500']] as [$service, $count, $width, $color])
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-sm">{{ $service }}</span>
                <span class="text-xs font-bold text-muted">{{ $count }} servis</span>
              </div>
              <div class="w-full h-2 bg-brand-surface rounded-full overflow-hidden border border-brand-primary/5">
                <div class="{{ $width }} h-full {{ $color }} rounded-full transition-all duration-1000">
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script>
    const isLight = document.documentElement.classList.contains('light');
    const mutedColor = isLight ? '#64748b' : '#94a3b8';
    const gridColor = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';

    Chart.defaults.color = mutedColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.font.family = 'Inter';

    const tooltipConfig = {
      backgroundColor: isLight ? '#fff' : '#1e1e1e',
      titleColor: isLight ? '#111' : '#fff',
      bodyColor: isLight ? '#444' : '#ccc',
      borderColor: gridColor,
      borderWidth: 1,
      padding: 12,
      cornerRadius: 12
    };

    // ── Trend Area Chart ──
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const incomeGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
    incomeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
    incomeGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    const expenseGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
    expenseGradient.addColorStop(0, 'rgba(179, 50, 50, 0.25)');
    expenseGradient.addColorStop(1, 'rgba(179, 50, 50, 0)');

    new Chart(trendCtx, {
      type: 'line',
      data: {
        labels: ['1 Apr', '5 Apr', '10 Apr', '15 Apr', '20 Apr', '25 Apr', '30 Apr', '5 Mei', '8 Mei'],
        datasets: [{
            label: 'Penghasilan',
            data: [1200, 1800, 1500, 2200, 1900, 2800, 2400, 3100, 2700],
            borderColor: '#10B981',
            backgroundColor: incomeGradient,
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointRadius: 0,
            pointHoverRadius: 6,
          },
          {
            label: 'Pengeluaran',
            data: [800, 600, 900, 700, 1100, 500, 850, 650, 750],
            borderColor: '#B33232',
            backgroundColor: expenseGradient,
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointRadius: 0,
            pointHoverRadius: 6,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          intersect: false,
          mode: 'index'
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            ...tooltipConfig,
            callbacks: {
              label: ctx => `${ctx.dataset.label}: Rp ${(ctx.parsed.y * 1000).toLocaleString('id-ID')}`
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            ticks: {
              callback: v => `${v/1000}jt`
            },
            grid: {
              color: gridColor
            }
          }
        }
      }
    });

    // ── Doughnut Chart ──
    new Chart(document.getElementById('doughnutChart'), {
      type: 'doughnut',
      data: {
        labels: ['Stok Barang', 'Operasional', 'Peralatan', 'Lainnya'],
        datasets: [{
          data: [65, 20, 10, 5],
          backgroundColor: ['#B33232', '#F97316', '#EAB308', '#64748b'],
          borderWidth: 0,
          hoverOffset: 8,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: tooltipConfig
        }
      }
    });

    // ── Bar Chart ──
    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels: ['Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
        datasets: [{
            label: 'Penghasilan',
            data: [8500, 9200, 7800, 10500, 11200, 12350],
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderRadius: 8,
          },
          {
            label: 'Pengeluaran',
            data: [3200, 3800, 2900, 4100, 3600, 4850],
            backgroundColor: 'rgba(179, 50, 50, 0.7)',
            borderRadius: 8,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 12,
              padding: 20,
              usePointStyle: true,
              pointStyle: 'circle'
            }
          },
          tooltip: {
            ...tooltipConfig,
            callbacks: {
              label: ctx => `${ctx.dataset.label}: Rp ${(ctx.parsed.y * 1000).toLocaleString('id-ID')}`
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            ticks: {
              callback: v => `${v/1000}jt`
            },
            grid: {
              color: gridColor
            }
          }
        }
      }
    });
  </script>
@endpush
