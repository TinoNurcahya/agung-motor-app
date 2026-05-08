{{-- ============================================================
     PARTIAL: partials/admin/topbar.blade.php
     Digunakan oleh: layouts/admin.blade.php
     ============================================================ --}}

<header
  class="h-16 shrink-0 bg-brand-surface/60 backdrop-blur-xl border-b border-brand-primary/10 flex items-center justify-between px-6 z-10">

  {{-- Mobile Hamburger --}}
  <button class="md:hidden text-gray-500 hover:text-white transition-colors" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars text-xl"></i>
  </button>

  {{-- Search Bar (Desktop) --}}
  <div class="hidden md:flex items-center gap-2 flex-1 max-w-xs">
    <div class="relative w-full">
      <input type="text" placeholder="Cari..."
        class="w-full bg-brand-surface border border-brand-primary/10 rounded-full px-4 py-2 pl-10 text-sm focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none">
      <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
    </div>
  </div>

  {{-- Right Side Actions --}}
  <div class="flex items-center gap-3">
    {{-- Theme Toggle --}}
    <button id="theme-toggle"
      class="w-9 h-9 glass rounded-full flex items-center justify-center text-muted hover:text-brand-primary transition-colors"
      title="Toggle Theme">
      <i class="fa-solid fa-moon" id="theme-icon-dark"></i>
      <i class="fa-solid fa-sun hidden" id="theme-icon-light"></i>
    </button>
  </div>

</header>
