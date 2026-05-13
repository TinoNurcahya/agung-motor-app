<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }} - Auth</title>

  {{-- Theme Flash Protection --}}
  <script>
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.classList.add('light');
    } else {
      document.documentElement.classList.remove('light');
    }
  </script>

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .auth-bg {
      background: linear-gradient(rgba(18, 18, 18, 0.8), rgba(18, 18, 18, 0.9)), url('/images/hero.png');
      background-size: cover;
      background-position: center;
    }

    html.light .auth-bg {
      background: linear-gradient(rgba(243, 244, 246, 0.85), rgba(243, 244, 246, 0.95)), url('/images/hero.png');
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes floatPulsate {
      0%, 100% { transform: translateY(0) scale(1); filter: drop-shadow(0 0 10px rgba(220, 38, 38, 0.2)); }
      50% { transform: translateY(-6px) scale(1.02); filter: drop-shadow(0 0 25px rgba(220, 38, 38, 0.5)); }
    }
    .animate-logo {
      animation: fadeInDown 0.8s ease-out forwards, floatPulsate 4s ease-in-out 0.8s infinite;
    }
    .animate-card {
      animation: fadeInUp 0.8s ease-out forwards;
    }
  </style>
</head>

<body class="font-sans antialiased auth-bg min-h-screen flex items-center justify-center p-6">

  {{-- Theme Toggle Float --}}
  <div class="fixed top-6 right-6">
    <button id="theme-toggle"
      class="w-10 h-10 rounded-full glass flex items-center justify-center text-muted hover:text-brand-primary transition-colors">
      <i class="fa-solid fa-moon" id="theme-icon-dark"></i>
      <i class="fa-solid fa-sun hidden" id="theme-icon-light"></i>
    </button>
  </div>

  <div class="w-full max-w-md">
    {{-- Logo / Brand --}}
    <div class="text-center -mb-4 md:-mb-6 relative z-20 animate-logo">
      <a href="/" class="inline-block">
        <img src="/images/logo.png" alt="Agung Motor" class="w-64 md:w-80 h-auto object-contain mx-auto transition-transform duration-300 hover:scale-105">
      </a>
    </div>

    {{-- Main Card --}}
    <div class="glass-card p-8 md:p-10 shadow-2xl relative overflow-hidden animate-card">
      {{-- Decorative Glow --}}
      <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand-primary/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-brand-primary/5 rounded-full blur-3xl"></div>

      <div class="relative z-10">
        {{ $slot }}
      </div>
    </div>

    {{-- Footer Info --}}
    <p class="text-center mt-8 text-xs text-muted font-medium uppercase tracking-widest">
      © {{ date('Y') }} Agung Motor App • Professional Service
    </p>
  </div>

  <script>
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
</body>

</html>
