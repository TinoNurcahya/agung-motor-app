{{-- ============================================================
     PARTIAL: partials/admin/sidebar.blade.php
     Digunakan oleh: layouts/admin.blade.php
     ============================================================ --}}

{{-- Mobile Overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden md:hidden"
  onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
<aside id="sidebar"
  class="fixed md:relative inset-y-0 left-0 z-40 w-64 bg-brand-surface/95 backdrop-blur-2xl border-r border-brand-primary/10 flex flex-col flex-shrink-0 transition-all duration-300 shadow-2xl">

  {{-- Logo --}}
  <div class="py-8 px-4 relative flex items-center justify-center bg-gradient-to-b from-brand-primary/15 via-brand-primary/5 to-transparent border-b border-brand-primary/10">
    <a href="{{ route('admin.index') }}" class="block w-full text-center hover:scale-105 transition-transform duration-500">
      <img src="/images/logo.png" alt="Agung Motor" class="h-32 w-full object-contain filter drop-shadow-[0_10px_15px_rgba(220,38,38,0.3)]">
    </a>
    <button class="md:hidden absolute right-3 top-3 w-8 h-8 rounded-full glass flex items-center justify-center text-muted hover:text-white hover:bg-brand-primary transition-all" onclick="toggleSidebar()">
      <i class="fa-solid fa-xmark text-sm"></i>
    </button>
  </div>

  {{-- Nav Links --}}
  <nav class="flex-1 px-4 space-y-1.5 py-4 overflow-y-auto scrollbar-hide">
    @php
      $navItems = [
          [
              'route' => 'admin.index',
              'active' => 'admin.index',
              'icon' => 'fa-gauge',
              'label' => 'Dashboard',
              'accent' => 'text-blue-400',
          ],
          [
              'route' => 'admin.penghasilan.index',
              'active' => 'admin.penghasilan.*',
              'icon' => 'fa-money-bill-trend-up',
              'label' => 'Penghasilan',
              'accent' => 'text-emerald-400',
          ],
          [
              'route' => 'admin.pengeluaran.index',
              'active' => 'admin.pengeluaran.*',
              'icon' => 'fa-file-invoice-dollar',
              'label' => 'Pengeluaran',
              'accent' => 'text-rose-400',
          ],
          [
              'route' => 'admin.produk.index',
              'active' => 'admin.produk.*',
              'icon' => 'fa-box',
              'label' => 'Kelola Produk',
              'accent' => 'text-amber-400',
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
        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 group
                      {{ $active
                          ? 'bg-gradient-to-r from-brand-primary to-red-800 text-white font-bold shadow-lg shadow-brand-primary/30 scale-[1.02]'
                          : 'text-muted hover:text-white hover:bg-brand-primary/10 hover:translate-x-1 hover:border-l-4 hover:border-brand-primary' }}">
        <i
          class="fa-solid {{ $item['icon'] }} w-5 text-center shrink-0 transition-transform group-hover:scale-110
                          {{ $active ? 'text-white' : $item['accent'] }}"></i>
        <span>{{ $item['label'] }}</span>
        @if ($active)
          <span class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></span>
        @endif
      </a>
    @endforeach
  </nav>

  {{-- Back to Website --}}
  <div class="px-4 pb-2 border-t border-brand-primary/5 pt-3">
    <a href="{{ route('home') }}"
      class="flex items-center gap-3 px-4 py-3 rounded-xl text-muted hover:text-white hover:bg-brand-primary/10 transition-all duration-300 text-sm group hover:translate-x-1">
      <i class="fa-solid fa-arrow-left w-5 text-center shrink-0 group-hover:-translate-x-1 transition-transform"></i>
      <span>Kembali ke Website</span>
    </a>
  </div>

  {{-- User Profile --}}
  <div class="p-4 border-t border-brand-primary/10 bg-brand-surface/40">
    <div class="bg-gradient-to-r from-brand-primary/10 to-transparent border border-brand-primary/20 rounded-2xl p-3 flex items-center gap-3 shadow-inner hover:border-brand-primary/40 transition-colors">
      <div
        class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-red-800 flex items-center justify-center text-white font-extrabold text-sm shrink-0 shadow-lg shadow-brand-primary/30 animate-pulse">
        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
      </div>
      <div class="min-w-0 flex-1">
        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
        <p class="text-[10px] text-brand-primary font-black truncate uppercase tracking-widest">Administrator</p>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" title="Logout" class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-white hover:bg-brand-primary/20 transition-all text-xs">
          <i class="fa-solid fa-sign-out"></i>
        </button>
      </form>
    </div>
  </div>

</aside>
