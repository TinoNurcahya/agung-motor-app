{{-- ============================================================
     PARTIAL: partials/landing/navbar.blade.php
     Digunakan oleh: layouts/landing.blade.php
     ============================================================ --}}

<header id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 glass border-b border-brand-primary/5">
  <nav class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-3">
      <img src="/images/logo.png" alt="Agung Motor" class="h-16 object-contain">
    </a>

    {{-- Desktop Nav --}}
    <ul class="hidden md:flex items-center gap-8 text-sm font-medium">
      @foreach ([['home', 'Beranda'], ['service', 'Layanan'], ['about', 'Tentang'], ['shop', 'Toko'], ['contact', 'Kontak']] as [$route, $label])
        <li>
          <a href="{{ route($route) }}"
            class="hover:text-brand-primary transition-colors
                          {{ request()->routeIs($route) ? 'text-brand-primary' : 'text-muted hover:text-brand-primary' }}">
            {{ $label }}
          </a>
        </li>
      @endforeach
    </ul>

    {{-- Right CTAs --}}
    <div class="flex items-center gap-4">


      {{-- Theme Toggle --}}
      <button id="theme-toggle"
        class="w-10 h-10 rounded-full glass flex items-center justify-center text-muted hover:text-brand-primary transition-colors"
        title="Toggle Theme">
        <i class="fa-solid fa-moon" id="theme-icon-dark"></i>
        <i class="fa-solid fa-sun hidden" id="theme-icon-light"></i>
      </button>

      @if (Route::has('login'))
        @auth
          {{-- User Dropdown --}}
          <div class="relative hidden sm:inline-block text-left" id="user-dropdown-wrapper">
            <button type="button" id="user-menu-button"
              class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-red-800 flex items-center justify-center text-white font-bold text-sm shadow-md hover:scale-105 transition-transform">
              {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </button>

            {{-- Dropdown Menu --}}
            <div id="user-dropdown-menu"
              class="hidden absolute right-0 mt-3 w-48 rounded-xl bg-brand-surface-solid border border-brand-primary/10 shadow-2xl z-50 overflow-hidden animate-fade-in">
              <div class="px-4 py-3 border-b border-brand-primary/5">
                <p class="text-[10px] text-muted uppercase tracking-wider font-bold">User Profile</p>
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
              </div>
              <div class="py-1">
                <a href="{{ route('admin.index') }}"
                  class="flex items-center gap-2 px-4 py-2 text-sm text-muted hover:bg-brand-primary/5 hover:text-brand-primary transition-colors">
                  <i class="fa-solid fa-gauge w-4"></i> Admin Panel
                </a>
                <a href="{{ route('profile.edit') }}"
                  class="flex items-center gap-2 px-4 py-2 text-sm text-muted hover:bg-brand-primary/5 hover:text-brand-primary transition-colors">
                  <i class="fa-solid fa-user w-4"></i> Profile
                </a>
              </div>
              <div class="py-1 border-t border-brand-primary/5">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-brand-primary hover:bg-brand-primary/10 transition-colors">
                    <i class="fa-solid fa-sign-out w-4"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}"
            class="px-5 py-2.5 rounded-full border border-brand-primary/20 text-sm font-bold text-muted hover:text-brand-primary hover:border-brand-primary/50 hover:bg-brand-primary/5 transition-all hidden sm:flex items-center gap-2">
            <i class="fa-solid fa-circle-user text-lg"></i>
            Masuk
          </a>
        @endauth
      @endif

      {{-- Mobile Hamburger --}}
      <button id="mob-toggle"
        class="md:hidden w-10 h-10 rounded-full glass flex items-center justify-center text-muted hover:text-brand-primary transition-all"
        aria-label="Menu">
        <i class="fa-solid fa-bars text-lg"></i>
      </button>
    </div>
  </nav>

  {{-- Mobile Dropdown Menu --}}
  <div id="mob-menu"
    class="hidden md:hidden border-t border-brand-primary/10 px-6 py-6 space-y-4 bg-brand-surface-solid animate-fade-in-down">
    @foreach ([['home', 'Beranda', 'fa-home'], ['service', 'Layanan', 'fa-tools'], ['about', 'Tentang', 'fa-info-circle'], ['shop', 'Toko', 'fa-shopping-bag'], ['contact', 'Kontak', 'fa-envelope']] as [$route, $label, $icon])
      <a href="{{ route($route) }}"
        class="flex items-center gap-3 py-3 px-4 rounded-xl text-sm font-bold transition-all
            {{ request()->routeIs($route)
                ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20'
                : 'text-muted hover:bg-brand-primary/5 hover:text-brand-primary' }}">
        <i class="fa-solid {{ $icon }} w-5"></i>
        {{ $label }}
      </a>
    @endforeach

    <div class="pt-6 border-t border-brand-primary/10 space-y-4">
      @auth
        <div class="flex items-center gap-3 px-4 py-2 bg-brand-primary/5 rounded-2xl">
          <div
            class="w-10 h-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold text-sm">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
          </div>
          <div class="min-w-0">
            <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-muted uppercase">Premium Member</p>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <a href="{{ route('admin.index') }}"
            class="flex flex-col items-center gap-2 p-4 rounded-xl bg-brand-surface border border-brand-primary/10 text-muted hover:text-brand-primary hover:border-brand-primary/30 transition-all">
            <i class="fa-solid fa-gauge text-lg"></i>
            <span class="text-[10px] font-bold uppercase">Admin</span>
          </a>
          <a href="{{ route('profile.edit') }}"
            class="flex flex-col items-center gap-2 p-4 rounded-xl bg-brand-surface border border-brand-primary/10 text-muted hover:text-brand-primary hover:border-brand-primary/30 transition-all">
            <i class="fa-solid fa-user text-lg"></i>
            <span class="text-[10px] font-bold uppercase">Profile</span>
          </a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="w-full flex items-center justify-center gap-2 py-4 rounded-xl bg-brand-primary/10 text-brand-primary font-bold text-sm hover:bg-brand-primary hover:text-white transition-all">
            <i class="fa-solid fa-sign-out"></i> Logout
          </button>
        </form>
      @else
        <div class="pt-2">
          <a href="{{ route('login') }}"
            class="flex items-center justify-center gap-3 py-4 rounded-xl glass text-muted font-bold text-sm hover:text-brand-primary transition-all">
            <i class="fa-solid fa-circle-user text-lg"></i>
            Masuk ke Akun
          </a>
        </div>
      @endauth
    </div>
  </div>
</header>
