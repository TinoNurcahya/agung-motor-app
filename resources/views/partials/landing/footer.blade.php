{{-- ============================================================
     PARTIAL: partials/landing/footer.blade.php
     Digunakan oleh: layouts/landing.blade.php
     ============================================================ --}}

<footer class="bg-brand-surface border-t border-brand-primary/10">
  <div class="max-w-7xl mx-auto px-6 py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-12">

    {{-- Brand Column --}}
    <div class="space-y-6 lg:col-span-2">
      <a href="{{ route('home') }}" class="text-2xl font-extrabold italic tracking-tighter">
        AGUNG <span class="text-brand-primary">MOTOR</span>
      </a>
      <p class="text-sm text-muted max-w-sm leading-relaxed">
        Bengkel motor professional dengan mekanik bersertifikat dan suku cadang original. Memberikan pelayanan terbaik
        untuk performa kendaraan Anda sejak 2009.
      </p>
      <div class="flex gap-4">
        <a href="https://wa.me/6281234567890" class="btn-whatsapp py-3 px-6 text-sm inline-flex items-center gap-2">
          <i class="fa-brands fa-whatsapp text-lg"></i> Konsultasi Gratis
        </a>
      </div>
    </div>

    {{-- Navigation Links --}}
    <div class="space-y-6">
      <h4 class="font-bold text-xs uppercase tracking-widest text-brand-primary">Eksplorasi</h4>
      <ul class="space-y-4 text-sm">
        @foreach ([['home', 'Beranda'], ['service', 'Layanan'], ['shop', 'Toko'], ['about', 'Tentang'], ['contact', 'Kontak']] as [$route, $label])
          <li>
            <a href="{{ route($route) }}"
              class="text-muted hover:text-brand-primary transition-colors flex items-center gap-2 group">
              <span
                class="w-1.5 h-1.5 rounded-full bg-brand-primary opacity-0 group-hover:opacity-100 transition-opacity"></span>
              {{ $label }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    {{-- Contact Info --}}
    <div class="space-y-6">
      <h4 class="font-bold text-xs uppercase tracking-widest text-brand-primary">Kontak Kami</h4>
      <ul class="space-y-5 text-sm">
        <li class="flex items-start gap-3 text-muted">
          <div
            class="w-8 h-8 rounded-lg bg-brand-primary/10 flex items-center justify-center shrink-0 text-brand-primary">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <span>Jl. Karangmulya II No.74, Drajat, Kec. Kesambi, Kota Cirebon, Jawa Barat 45133</span>
        </li>
        <li class="flex items-center gap-3 text-muted">
          <div
            class="w-8 h-8 rounded-lg bg-brand-whatsapp/10 flex items-center justify-center shrink-0 text-brand-whatsapp">
            <i class="fa-brands fa-whatsapp"></i>
          </div>
          <span>+62 812 3456 7890</span>
        </li>
        <li class="flex items-center gap-3 text-muted">
          <div
            class="w-8 h-8 rounded-lg bg-brand-primary/10 flex items-center justify-center shrink-0 text-brand-primary">
            <i class="fa-solid fa-clock"></i>
          </div>
          <span>Senin – Sabtu, 08:00 – 17:00 WIB</span>
        </li>
      </ul>
    </div>
  </div>

  <div class="border-t border-brand-primary/5 py-8">
    <div
      class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] uppercase tracking-widest font-bold text-muted">
      <p>© {{ date('Y') }} Agung Motor App. All Rights Reserved.</p>
      <div class="flex gap-6">
        <a href="#" class="hover:text-brand-primary transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-brand-primary transition-colors">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>

{{-- WhatsApp Float Button --}}
<a href="https://wa.me/6281234567890" target="_blank" rel="noopener"
  class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-brand-whatsapp shadow-lg shadow-brand-whatsapp/30 flex items-center justify-center text-white text-2xl hover:scale-110 hover:-rotate-12 transition-all active:scale-95"
  aria-label="Chat WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>

<script>
  // Mobile menu toggle
  const mobToggle = document.getElementById('mob-toggle');
  const mobMenu = document.getElementById('mob-menu');
  const navbar = document.getElementById('navbar');

  if (mobToggle && mobMenu) {
    mobToggle.addEventListener('click', () => {
      const isOpen = !mobMenu.classList.contains('hidden');
      mobMenu.classList.toggle('hidden');

      // Toggle icon bars to xmark
      const icon = mobToggle.querySelector('i');
      if (isOpen) {
        icon.classList.replace('fa-xmark', 'fa-bars');
      } else {
        icon.classList.replace('fa-bars', 'fa-xmark');
      }
    });
  }

  // Scroll effect for navbar
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.classList.add('py-2', 'shadow-lg');
    } else {
      navbar.classList.remove('py-2', 'shadow-lg');
    }
  });

  // User Dropdown toggle
  const userMenuButton = document.getElementById('user-menu-button');
  const userDropdownMenu = document.getElementById('user-dropdown-menu');
  const userDropdownWrapper = document.getElementById('user-dropdown-wrapper');

  if (userMenuButton && userDropdownMenu) {
    userMenuButton.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!userDropdownMenu.classList.contains('hidden')) {
        if (userDropdownWrapper && !userDropdownWrapper.contains(e.target)) {
          userDropdownMenu.classList.add('hidden');
        }
      }
    });
  }

  // Theme Toggle Logic
  const themeToggle = document.getElementById('theme-toggle');
  const html = document.documentElement;
  const themeIconDark = document.getElementById('theme-icon-dark');
  const themeIconLight = document.getElementById('theme-icon-light');

  function updateThemeUI(theme) {
    if (theme === 'light') {
      html.classList.add('light');
      if (themeIconDark) themeIconDark.classList.add('hidden');
      if (themeIconLight) themeIconLight.classList.remove('hidden');
    } else {
      html.classList.remove('light');
      if (themeIconDark) themeIconDark.classList.remove('hidden');
      if (themeIconLight) themeIconLight.classList.add('hidden');
    }
  }

  const savedTheme = localStorage.getItem('theme') || 'dark';
  updateThemeUI(savedTheme);

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const isLight = html.classList.contains('light');
      const newTheme = isLight ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
      updateThemeUI(newTheme);
    });
  }
</script>
