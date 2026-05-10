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
      <div></div>
      <div class="flex items-center gap-3 text-sm">
        <select id="periodFilter" onchange="updateCharts()"
          class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
          <option value="7" {{ request('period', 30) == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
          <option value="30" {{ request('period', 30) == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
          <option value="90" {{ request('period', 30) == 90 ? 'selected' : '' }}>3 Bulan Terakhir</option>
        </select>
        <a href="{{ route('admin.statistik.export.pdf', ['period' => request('period', 30)]) }}" 
           class="glass px-4 py-2 rounded-lg text-muted hover:text-brand-primary transition-colors text-xs">
          <i class="fa-solid fa-download mr-2"></i> Export PDF
        </a>
      </div>
    </div>

    {{-- Summary Strip --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Total Penghasilan</p>
        <p class="text-2xl font-bold text-brand-income">Rp {{ number_format($summary['total_penghasilan'] ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs {{ ($summary['penghasilan_change'] ?? 0) >= 0 ? 'text-brand-income' : 'text-brand-expense' }} mt-1">
          <i class="fa-solid fa-arrow-{{ ($summary['penghasilan_change'] ?? 0) >= 0 ? 'up' : 'down' }} text-[10px]"></i> 
          {{ number_format(abs($summary['penghasilan_change'] ?? 0), 1) }}%
        </p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Total Pengeluaran</p>
        <p class="text-2xl font-bold text-brand-expense">Rp {{ number_format($summary['total_pengeluaran'] ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs {{ ($summary['pengeluaran_change'] ?? 0) <= 0 ? 'text-brand-income' : 'text-brand-expense' }} mt-1">
          <i class="fa-solid fa-arrow-{{ ($summary['pengeluaran_change'] ?? 0) <= 0 ? 'down' : 'up' }} text-[10px]"></i> 
          {{ number_format(abs($summary['pengeluaran_change'] ?? 0), 1) }}%
        </p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Laba Bersih</p>
        <p class="text-2xl font-bold">Rp {{ number_format($summary['laba_bersih'] ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs {{ ($summary['laba_change'] ?? 0) >= 0 ? 'text-brand-income' : 'text-brand-expense' }} mt-1">
          <i class="fa-solid fa-arrow-{{ ($summary['laba_change'] ?? 0) >= 0 ? 'up' : 'down' }} text-[10px]"></i> 
          {{ number_format(abs($summary['laba_change'] ?? 0), 1) }}%
        </p>
      </div>
      <div class="glass-card p-5 text-center">
        <p class="text-muted text-[10px] uppercase tracking-widest mb-1">Margin Profit</p>
        <p class="text-2xl font-bold">{{ number_format($summary['margin_profit'] ?? 0, 1) }}<span class="text-lg text-muted">%</span></p>
      </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid lg:grid-cols-3 gap-6">

      {{-- Main Area Chart --}}
      <div class="glass-card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
          <h4 class="font-bold">Tren Keuangan</h4>
          <div class="flex gap-4 text-xs text-muted">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-income"></span> Penghasilan</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-expense"></span> Pengeluaran</span>
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
        <div class="mt-6 space-y-3" id="doughnutLegend">
          {{-- Legend akan diisi JavaScript --}}
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
        <div class="space-y-4" id="topServices">
          {{-- Akan diisi JavaScript --}}
        </div>
      </div>

    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script>
    // Data dari controller
    const trendData = @json($trendData);
    const doughnutData = @json($doughnutData);
    const barChartData = @json($barChartData);
    const topServices = @json($topServices);
    
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
        labels: trendData.labels,
        datasets: [{
            label: 'Penghasilan',
            data: trendData.income,
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
            data: trendData.expense,
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
          x: { grid: { display: false } },
          y: {
            ticks: { callback: v => `${v}K` },
            grid: { color: gridColor }
          }
        }
      }
    });

    // ── Doughnut Chart ──
    if (doughnutData.labels && doughnutData.labels.length > 0) {
      new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
          labels: doughnutData.labels,
          datasets: [{
            data: doughnutData.data,
            backgroundColor: doughnutData.colors,
            borderWidth: 0,
            hoverOffset: 8,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '72%',
          plugins: {
            legend: { display: false },
            tooltip: tooltipConfig
          }
        }
      });
      
      // Isi legend
      const legendHtml = doughnutData.labels.map((label, i) => `
        <div class="flex items-center justify-between text-sm">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full" style="background-color: ${doughnutData.colors[i]}"></span>
            <span class="text-muted">${label}</span>
          </div>
          <span class="font-bold">${doughnutData.data[i]}%</span>
        </div>
      `).join('');
      document.getElementById('doughnutLegend').innerHTML = legendHtml;
    }

    // ── Bar Chart ──
    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels: barChartData.labels,
        datasets: [{
            label: 'Penghasilan',
            data: barChartData.income,
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderRadius: 8,
          },
          {
            label: 'Pengeluaran',
            data: barChartData.expense,
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
          x: { grid: { display: false } },
          y: {
            ticks: { callback: v => `${v}K` },
            grid: { color: gridColor }
          }
        }
      }
    });
    
    // ── Top Services ──
    if (topServices.length > 0) {
      const servicesHtml = topServices.map(service => `
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-sm">${service.name}</span>
            <span class="text-xs font-bold text-muted">${service.count} servis</span>
          </div>
          <div class="w-full h-2 bg-brand-surface rounded-full overflow-hidden border border-brand-primary/5">
            <div class="${service.width} h-full ${service.color} rounded-full transition-all duration-1000"></div>
          </div>
        </div>
      `).join('');
      document.getElementById('topServices').innerHTML = servicesHtml;
    }

    // Filter period
    function updateCharts() {
      const period = document.getElementById('periodFilter').value;
      window.location.href = `{{ route('admin.statistik') }}?period=${period}`;
    }
  </script>
@endpush