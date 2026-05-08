{{-- ============================================================
     PARTIAL: partials/admin/sidebar.blade.php
     Digunakan oleh: layouts/admin.blade.php
     ============================================================ --}}

{{-- Mobile Overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden md:hidden"
  onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
<aside id="sidebar"
  class="fixed md:relative inset-y-0 left-0 z-40 w-64 bg-brand-surface border-r border-brand-primary/10 flex flex-col flex-shrink-0 transition-transform duration-300">

  {{-- Logo --}}
  <div class="p-6 flex items-center justify-between">
    <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
      <div
        class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg shadow-brand-primary/30">
        A</div>
      <span class="text-xl font-bold tracking-tight">Agung <span class="text-brand-primary">Motor</span></span>
    </a>
    <button class="md:hidden text-muted hover:text-brand-primary transition-colors" onclick="toggleSidebar()">
      <i class="fa-solid fa-xmark text-lg"></i>
    </button>
  </div>

  {{-- Nav Links --}}
  <nav class="flex-1 px-4 space-y-1 py-2">
    @php
      $navItems = [
          [
              'route' => 'admin.index',
              'active' => 'admin.index',
              'icon' => 'fa-gauge',
              'label' => 'Dashboard',
              'accent' => '',
          ],
          [
              'route' => 'admin.penghasilan.index',
              'active' => 'admin.penghasilan.*',
              'icon' => 'fa-money-bill-trend-up',
              'label' => 'Penghasilan',
              'accent' => 'text-brand-income',
          ],
          [
              'route' => 'admin.pengeluaran.index',
              'active' => 'admin.pengeluaran.*',
              'icon' => 'fa-file-invoice-dollar',
              'label' => 'Pengeluaran',
              'accent' => 'text-brand-expense',
          ],
          [
              'route' => 'admin.produk.index',
              'active' => 'admin.produk.*',
              'icon' => 'fa-box',
              'label' => 'Kelola Produk',
              'accent' => 'text-orange-400',
          ],
          [
              'route' => 'admin.statistik',
              'active' => 'admin.statistik',
              'icon' => 'fa-chart-line',
              'label' => 'Statistik',
              'accent' => 'text-purple-400',
          ],
          [
              'route' => 'admin.ai',
              'active' => 'admin.ai',
              'icon' => 'fa-brain',
              'label' => 'AI Analitik',
              'accent' => 'text-cyan-400',
          ],
      ];
    @endphp

    @foreach ($navItems as $item)
      @php $active = request()->routeIs($item['active']); @endphp
      <a href="{{ route($item['route']) }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200
                      {{ $active
                          ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20'
                          : 'text-muted hover:bg-brand-primary/5 hover:text-brand-primary' }}">
        <i
          class="fa-solid {{ $item['icon'] }} w-5 text-center shrink-0
                          {{ $active ? 'text-white' : $item['accent'] }}"></i>
        <span>{{ $item['label'] }}</span>
        @if ($active)
          <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white/70"></span>
        @endif
      </a>
    @endforeach
  </nav>

  {{-- Back to Website --}}
  <div class="px-4 pb-2">
    <a href="{{ route('home') }}"
      class="flex items-center gap-3 px-4 py-3 rounded-xl text-muted hover:bg-brand-primary/5 hover:text-brand-primary transition-all duration-200 text-sm">
      <i class="fa-solid fa-arrow-left w-5 text-center shrink-0"></i>
      <span>Kembali ke Website</span>
    </a>
  </div>

  {{-- User Profile --}}
  <div class="p-4 border-t border-brand-primary/10">
    <div class="bg-brand-primary/5 border border-brand-primary/10 rounded-xl p-3 flex items-center gap-3">
      <div
        class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-primary to-red-800 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md">
        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
      </div>
      <div class="min-w-0 flex-1">
        <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
        <p class="text-xs text-muted truncate uppercase tracking-wider font-bold">Administrator</p>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" title="Logout" class="text-muted hover:text-brand-primary transition-colors text-xs">
          <i class="fa-solid fa-sign-out"></i>
        </button>
      </form>
    </div>
  </div>

</aside>
