<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta_description', 'Agung Motor — Bengkel motor modern, profesional, dan terpercaya di Jakarta.')">
  <title>@yield('title', 'Agung Motor — Bengkel Motor Modern')</title>

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
  {{-- Vite Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Per-page styles --}}
  @stack('styles')

  <style>
    html {
      scroll-behavior: smooth;
    }

    .hero-bg {
      background: linear-gradient(rgba(18, 18, 18, 0.75), rgba(18, 18, 18, 0.95)), url('/images/hero.png');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>

<body class="font-sans antialiased">

  @include('partials.landing.navbar')

  <main>
    @yield('content')
  </main>

  @include('partials.landing.footer')

  {{-- Per-page scripts --}}
  @stack('scripts')

</body>

</html>
