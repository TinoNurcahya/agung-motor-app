<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard') — Agung Motor</title>

  {{-- Theme Flash Protection --}}
  <script>
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.classList.add('light');
    } else {
      document.documentElement.classList.remove('light');
    }
  </script>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  {{-- Vite Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Per-page styles --}}
  @stack('styles')

  <style>
    #sidebar {
      transition: transform 0.3s ease;
    }

    @media (max-width: 767px) {
      #sidebar {
        transform: translateX(-100%);
      }

      #sidebar.sidebar-open {
        transform: translateX(0);
      }
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body class="font-sans antialiased overflow-hidden">
  <div class="flex h-screen overflow-hidden">

    @include('partials.admin.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

      @include('partials.admin.topbar')

      <main class="flex-1 overflow-y-auto p-6 scrollbar-hide">
        @yield('content')
      </main>

    </div>

  </div>

  {{-- Per-page scripts --}}
  @stack('scripts')

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      const isOpen = sidebar.classList.contains('sidebar-open');
      sidebar.classList.toggle('sidebar-open', !isOpen);
      overlay.classList.toggle('hidden', isOpen);
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

    // Initialize
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
