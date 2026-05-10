@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
  <div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold">Dashboard Overview</h1>
        <p class="text-muted text-sm">Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }}!</p>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <span class="glass px-4 py-2 rounded-lg text-muted">
          <i class="fa-solid fa-calendar-days mr-2"></i>{{ date('d M Y') }}
        </span>
        <a href="{{ route('admin.penghasilan.create') }}" class="btn-primary py-2 px-4 rounded-lg text-xs">
          <i class="fa-solid fa-plus mr-2"></i>Input Transaksi
        </a>
      </div>
    </div>

    {{-- Overview Cards --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="glass-card p-6 group hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
          <div
            class="w-12 h-12 rounded-xl bg-brand-income/10 flex items-center justify-center text-brand-income group-hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-arrow-trend-up text-xl"></i>
          </div>
          <span class="text-brand-income text-xs font-bold bg-brand-income/10 px-2 py-1 rounded">
            {{ $overview['penghasilan_change'] >= 0 ? '+' : '' }}{{ number_format($overview['penghasilan_change'], 1) }}%
          </span>
        </div>
        <p class="text-muted text-xs font-medium uppercase tracking-wider">Total Penghasilan</p>
        <h3 class="text-3xl font-bold text-brand-income mt-1">
          Rp {{ number_format($overview['total_penghasilan'] ?? 0, 0, ',', '.') }}
        </h3>
        <a href="{{ route('admin.penghasilan.index') }}"
          class="text-xs text-muted hover:text-brand-income mt-3 inline-block transition-colors">Lihat Detail →</a>
      </div>

      <div class="glass-card p-6 group hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
          <div
            class="w-12 h-12 rounded-xl bg-brand-expense/10 flex items-center justify-center text-brand-expense group-hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-arrow-trend-down text-xl"></i>
          </div>
          <span class="text-brand-expense text-xs font-bold bg-brand-expense/10 px-2 py-1 rounded">
            {{ $overview['pengeluaran_change'] >= 0 ? '+' : '' }}{{ number_format($overview['pengeluaran_change'], 1) }}%
          </span>
        </div>
        <p class="text-muted text-xs font-medium uppercase tracking-wider">Total Pengeluaran</p>
        <h3 class="text-3xl font-bold text-brand-expense mt-1">
          Rp {{ number_format($overview['total_pengeluaran'] ?? 0, 0, ',', '.') }}
        </h3>
        <a href="{{ route('admin.pengeluaran.index') }}"
          class="text-xs text-muted hover:text-brand-expense mt-3 inline-block transition-colors">Lihat Detail →</a>
      </div>

      <div
        class="glass-card p-6 bg-gradient-to-br from-brand-primary/10 to-transparent group hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
          <div
            class="w-12 h-12 rounded-xl bg-brand-primary/20 flex items-center justify-center text-brand-primary group-hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-wallet text-xl"></i>
          </div>
        </div>
        <p class="text-muted text-xs font-medium uppercase tracking-wider">Laba Bersih</p>
        <h3 class="text-3xl font-bold mt-1">
          Rp {{ number_format($overview['laba_bersih'] ?? 0, 0, ',', '.') }}
        </h3>
        <a href="{{ route('admin.statistik') }}"
          class="text-xs text-muted hover:text-brand-primary mt-3 inline-block transition-colors">Lihat Statistik →</a>
      </div>
    </div>

    {{-- Chart --}}
    <div class="glass-card p-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <h4 class="font-bold">Statistik Keuangan</h4>
        <div class="flex items-center gap-4">
          <div class="flex gap-4 text-xs text-muted">
            <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand-income"></span>
              Penghasilan</span>
            <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand-expense"></span>
              Pengeluaran</span>
          </div>
          <select id="chartPeriod"
            class="bg-brand-surface border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-0">
            <option value="7" {{ request('period', 30) == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="30" {{ request('period', 30) == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
            <option value="90" {{ request('period', 30) == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
          </select>
        </div>
      </div>
      <div class="h-72"><canvas id="dashboardChart"></canvas></div>
    </div>

    {{-- Quick Actions + Recent Transactions --}}
    <div class="grid lg:grid-cols-3 gap-6">

      {{-- Quick Actions --}}
      <div class="glass-card p-6 space-y-4">
        <h4 class="font-bold mb-2">Aksi Cepat</h4>
        <a href="{{ route('admin.penghasilan.create') }}"
          class="flex items-center gap-4 p-4 rounded-xl bg-brand-income/5 hover:bg-brand-income/10 transition-colors group">
          <div
            class="w-10 h-10 rounded-lg bg-brand-income/10 flex items-center justify-center text-brand-income group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-plus"></i>
          </div>
          <div>
            <p class="font-semibold text-sm">Input Penghasilan</p>
            <p class="text-xs text-muted">Catat pemasukan baru</p>
          </div>
        </a>
        <a href="{{ route('admin.pengeluaran.create') }}"
          class="flex items-center gap-4 p-4 rounded-xl bg-brand-expense/5 hover:bg-brand-expense/10 transition-colors group">
          <div
            class="w-10 h-10 rounded-lg bg-brand-expense/10 flex items-center justify-center text-brand-expense group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-minus"></i>
          </div>
          <div>
            <p class="font-semibold text-sm">Input Pengeluaran</p>
            <p class="text-xs text-muted">Catat belanja operasional</p>
          </div>
        </a>
        <a href="{{ route('admin.statistik') }}"
          class="flex items-center gap-4 p-4 rounded-xl bg-brand-surface hover:bg-brand-primary/5 transition-colors group border border-brand-primary/5">
          <div
            class="w-10 h-10 rounded-lg bg-brand-primary/10 flex items-center justify-center text-brand-primary group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-chart-pie"></i>
          </div>
          <div>
            <p class="font-semibold text-sm">Lihat Statistik</p>
            <p class="text-xs text-muted">Analisis keuangan lengkap</p>
          </div>
        </a>
      </div>

      {{-- Recent Transactions --}}
      <div class="glass-card overflow-hidden lg:col-span-2">
        <div class="p-6 flex items-center justify-between border-b border-brand-primary/10">
          <h4 class="font-bold">Transaksi Terakhir</h4>
          <a href="{{ route('admin.penghasilan.index') }}"
            class="text-xs text-brand-primary font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="text-muted text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Tanggal</th>
                <th class="px-6 py-4 font-semibold">Kategori</th>
                <th class="px-6 py-4 font-semibold">Keterangan</th>
                <th class="px-6 py-4 font-semibold text-right">Nominal</th>
                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-brand-primary/5">
              @forelse($recentTransactions as $item)
                <tr class="hover:bg-brand-primary/5 transition-colors">
                  <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $item->type === 'income' ? 'bg-brand-income/10 text-brand-income' : 'bg-brand-expense/10 text-brand-expense' }}">
                      {{ $item->kategori }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm">{{ Str::limit($item->keterangan, 30) }}</td>
                  <td class="px-6 py-4 text-sm font-bold text-right {{ $item->type === 'income' ? 'text-brand-income' : 'text-brand-expense' }}">
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                      @if($item->kategori === 'Penghasilan')
                        <a href="{{ route('admin.penghasilan.edit', $item->id) }}"
                          class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center">
                          <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </a>
                        <form action="{{ route('admin.penghasilan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all">
                            <i class="fa-solid fa-trash text-xs"></i>
                          </button>
                        </form>
                      @else
                        <a href="{{ route('admin.pengeluaran.edit', $item->id) }}"
                          class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center">
                          <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </a>
                        <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all">
                            <i class="fa-solid fa-trash text-xs"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-6 py-12 text-center text-muted">
                    <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                    Belum ada transaksi
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script>
    // Data dari controller
    const chartData = @json($chartData);
    const currentPeriod = {{ $period }};
    
    const isLight = document.documentElement.classList.contains('light');
    const mutedColor = isLight ? '#64748b' : '#94a3b8';
    const gridColor = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';

    Chart.defaults.color = mutedColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.font.family = 'Inter';

    let chartInstance = null;

    function renderChart(labels, incomeData, expenseData) {
      const ctx = document.getElementById('dashboardChart').getContext('2d');
      
      // Destroy existing chart if exists
      if (chartInstance) {
        chartInstance.destroy();
      }
      
      const incomeGrad = ctx.createLinearGradient(0, 0, 0, 300);
      incomeGrad.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
      incomeGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');
      
      const expenseGrad = ctx.createLinearGradient(0, 0, 0, 300);
      expenseGrad.addColorStop(0, 'rgba(179, 50, 50, 0.3)');
      expenseGrad.addColorStop(1, 'rgba(179, 50, 50, 0)');
      
      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: 'Penghasilan',
              data: incomeData,
              borderColor: '#10B981',
              backgroundColor: incomeGrad,
              fill: true,
              tension: 0.4,
              borderWidth: 2.5,
              pointRadius: 0,
              pointHoverRadius: 6
            },
            {
              label: 'Pengeluaran',
              data: expenseData,
              borderColor: '#B33232',
              backgroundColor: expenseGrad,
              fill: true,
              tension: 0.4,
              borderWidth: 2.5,
              pointRadius: 0,
              pointHoverRadius: 6
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
              backgroundColor: isLight ? '#fff' : '#1e1e1e',
              titleColor: isLight ? '#111' : '#fff',
              bodyColor: isLight ? '#444' : '#ccc',
              borderColor: gridColor,
              borderWidth: 1,
              padding: 12,
              cornerRadius: 12,
              callbacks: {
                label: function(c) {
                  return c.dataset.label + ': Rp ' + (c.parsed.y * 1000).toLocaleString('id-ID');
                }
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
                callback: function(v) {
                  return v + 'K';
                }
              },
              grid: {
                color: gridColor
              }
            }
          }
        }
      });
    }
    
    // Initial render
    if (chartData && chartData.labels) {
      renderChart(chartData.labels, chartData.income, chartData.expense);
    }
    
    // Chart period change
    const periodSelect = document.getElementById('chartPeriod');
    if (periodSelect) {
      periodSelect.addEventListener('change', function() {
        const period = this.value;
        window.location.href = '{{ route("admin.index") }}?period=' + period;
      });
    }
  </script>
@endpush