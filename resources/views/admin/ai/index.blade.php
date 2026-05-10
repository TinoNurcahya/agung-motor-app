@extends('layouts.admin')

@section('title', 'AI Analitik Cerdas - Agung Motor')

@section('content')
  <div class="space-y-8 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span
            class="px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-500 text-[10px] font-black uppercase tracking-widest border border-cyan-500/20">AI
            Engine Active</span>
        </div>
        <h1 class="text-2xl font-black tracking-tight uppercase">Analitik <span class="text-cyan-400">Cerdas</span></h1>
        <p class="text-sm text-muted">Prediksi omset dan kalkulasi stok berbasis pola penjualan historis.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
          <p class="text-[10px] font-bold text-muted uppercase">Last Sync</p>
          <p class="text-xs font-bold" id="lastSync">Baru saja</p>
        </div>
        <button id="refreshBtn"
          class="w-10 h-10 rounded-xl glass flex items-center justify-center text-cyan-400 hover:scale-110 transition-transform">
          <i class="fa-solid fa-rotate"></i>
        </button>
      </div>
    </div>

    {{-- AI Insights Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Revenue Prediction Card --}}
      <div class="glass-card p-6 border-cyan-500/20 bg-cyan-500/[0.02] relative overflow-hidden group">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-colors"></div>
        <div class="relative z-10 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-500">
              <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest">Prediksi Omset</h3>
          </div>
          <div>
            <p class="text-3xl font-black text-cyan-400">Rp {{ number_format($predictionData['predicted_revenue'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-[10px] text-muted mt-1 uppercase font-bold tracking-tighter">Estimasi untuk {{ $predictionData['next_month'] ?? 'bulan depan' }}</p>
          </div>
          <div class="flex items-center gap-2 text-xs">
            <span class="text-green-500 font-bold flex items-center gap-1">
              <i class="fa-solid fa-arrow-trend-up"></i> +{{ number_format(abs($predictionData['percentage_change'] ?? 0), 1) }}%
            </span>
            <span class="text-muted">vs bulan lalu</span>
          </div>
        </div>
      </div>

      {{-- Stock Health Card --}}
      <div class="glass-card p-6 border-orange-500/20 bg-orange-500/[0.02] relative overflow-hidden group">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-colors"></div>
        <div class="relative z-10 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
              <i class="fa-solid fa-microchip"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest">Kalkulasi Stok</h3>
          </div>
          <div>
            <p class="text-3xl font-black text-orange-500">{{ $stockHealth['urgent_restock'] ?? 0 }} Item</p>
            <p class="text-[10px] text-muted mt-1 uppercase font-bold tracking-tighter">Butuh restok dalam 7 hari kedepan</p>
          </div>
          <div class="flex items-center gap-2 text-xs">
            <span class="text-orange-500 font-bold">Prioritas Tinggi</span>
            <span class="text-muted">• Berdasarkan tren pasar</span>
          </div>
        </div>
      </div>

      {{-- Customer Retention Card --}}
      <div class="glass-card p-6 border-purple-500/20 bg-purple-500/[0.02] relative overflow-hidden group">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
        <div class="relative z-10 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-500">
              <i class="fa-solid fa-users-gear"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest">Prediksi Pelanggan</h3>
          </div>
          <div>
            <p class="text-3xl font-black text-purple-500">{{ $customerRetention['retention_rate'] ?? 85.2 }}%</p>
            <p class="text-[10px] text-muted mt-1 uppercase font-bold tracking-tighter">Probabilitas pelanggan kembali</p>
          </div>
          <div class="flex items-center gap-2 text-xs">
            <span class="text-green-500 font-bold">{{ $customerRetention['status'] ?? 'Sangat Stabil' }}</span>
            <span class="text-muted">• Loyalitas meningkat</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Prediction Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- Revenue Forecast Chart --}}
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h4 class="font-bold">Forecast Pendapatan</h4>
            <p class="text-[10px] text-muted uppercase font-bold tracking-widest">Proyeksi 6 Bulan Kedepan</p>
          </div>
          <div class="flex gap-4 text-[10px] uppercase font-bold">
            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-cyan-400"></span> Prediksi AI</span>
            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-brand-primary/30"></span> Historis</span>
          </div>
        </div>
        <div class="h-72">
          <canvas id="forecastChart"></canvas>
        </div>
      </div>

      {{-- Smart Inventory List --}}
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h4 class="font-bold">Rekomendasi Restok</h4>
            <p class="text-[10px] text-muted uppercase font-bold tracking-widest">Analisis Kecepatan Barang Habis</p>
          </div>
          <a href="{{ route('admin.ai.restock') }}" class="text-[10px] font-black text-cyan-400 uppercase hover:underline">
            Lihat Semua
          </a>
        </div>
        <div class="space-y-4" id="restockList">
          @foreach($restockRecommendations as $item)
            <div class="p-4 rounded-xl bg-brand-surface border border-brand-primary/5 hover:border-cyan-500/30 transition-all group">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-brand-primary/5 flex items-center justify-center text-muted group-hover:text-cyan-400">
                    <i class="fa-solid fa-box-open text-xs"></i>
                  </div>
                  <span class="text-sm font-bold">{{ $item['name'] }}</span>
                </div>
                <span class="text-[10px] font-black uppercase {{ $item['color'] }}">{{ $item['time'] }}</span>
              </div>
              <div class="w-full h-1.5 bg-brand-surface border border-brand-primary/5 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-cyan-600 to-cyan-400 {{ $item['width'] }} rounded-full"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- AI Strategy Card --}}
    <div class="glass-card p-8 border-cyan-500/30 bg-cyan-500/[0.03] flex flex-col md:flex-row items-center gap-8">
      <div class="w-20 h-20 shrink-0 rounded-2xl bg-cyan-500/10 flex items-center justify-center text-cyan-500 text-4xl shadow-lg shadow-cyan-500/20">
        <i class="fa-solid fa-lightbulb"></i>
      </div>
      <div class="flex-1 space-y-2">
        <h4 class="text-lg font-black uppercase tracking-tight">AI Strategy <span class="text-cyan-400">Insight</span></h4>
        <p class="text-sm text-muted leading-relaxed" id="aiStrategyText">
          {{ $aiStrategy['text'] ?? 'Berdasarkan data 3 bulan terakhir, pelanggan cenderung melakukan servis rutin di minggu ke-2 setiap bulannya.' }}
        </p>
      </div>
      <button id="applyStrategyBtn" class="btn-primary py-3 px-8 text-sm bg-cyan-500 hover:bg-cyan-600 border-none shadow-cyan-500/20">
        Terapkan Strategi
      </button>
    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Data dari controller
    const forecastData = @json($forecastData);
    
    // Render chart setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
      const forecastCtx = document.getElementById('forecastChart').getContext('2d');
      
      const cyanGradient = forecastCtx.createLinearGradient(0, 0, 0, 300);
      cyanGradient.addColorStop(0, 'rgba(34, 211, 238, 0.2)');
      cyanGradient.addColorStop(1, 'rgba(34, 211, 238, 0)');
      
      // Gabungkan data historis dan forecast
      let allData = [];
      let allLabels = [];
      
      if (forecastData && forecastData.historical && forecastData.forecast) {
        allData = [...forecastData.historical, ...forecastData.forecast];
        allLabels = forecastData.labels;
      } else {
        // Fallback data
        allData = [35, 38, 32, 45, 42, 48, 52, 50];
        allLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'];
      }
      
      const historicalLength = forecastData ? forecastData.historical.length : 6;
      
      new Chart(forecastCtx, {
        type: 'line',
        data: {
          labels: allLabels,
          datasets: [{
            label: 'Omset (Jutaan)',
            data: allData,
            borderColor: '#22d3ee',
            backgroundColor: cyanGradient,
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#22d3ee',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            segment: {
              borderDash: function(ctx) {
                return ctx.p0DataIndex >= historicalLength ? [6, 6] : [];
              },
              borderColor: function(ctx) {
                return ctx.p0DataIndex >= historicalLength ? 'rgba(34, 211, 238, 0.5)' : '#22d3ee';
              }
            }
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return 'Omset: Rp ' + (context.parsed.y * 1000000).toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { font: { size: 10, weight: 'bold' } }
            },
            y: {
              grid: { color: 'rgba(255,255,255,0.05)' },
              ticks: {
                font: { size: 10 },
                callback: function(value) { return value + 'jt'; }
              }
            }
          }
        }
      });
    });
    
    // Refresh data dengan AJAX
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', function() {
        const btn = this;
        btn.classList.add('animate-spin');
        
        fetch('{{ route("admin.ai.refresh") }}')
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              location.reload();
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Gagal me-refresh data');
          })
          .finally(() => {
            btn.classList.remove('animate-spin');
          });
      });
    }
    
    // Apply Strategy button
    const applyBtn = document.getElementById('applyStrategyBtn');
    if (applyBtn) {
      applyBtn.addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;
        
        fetch('{{ route("admin.ai.apply-strategy") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            strategy: document.getElementById('aiStrategyText')?.innerText || ''
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
          } else {
            alert('Gagal menerapkan strategi');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        })
        .finally(() => {
          btn.innerHTML = originalText;
          btn.disabled = false;
        });
      });
    }
    
    // Update last sync time
    function updateLastSync() {
      const now = new Date();
      const timeStr = now.toLocaleTimeString('id-ID');
      const lastSyncEl = document.getElementById('lastSync');
      if (lastSyncEl) lastSyncEl.textContent = timeStr;
    }
    updateLastSync();
  </script>
@endpush