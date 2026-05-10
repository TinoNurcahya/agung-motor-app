@extends('layouts.app')

@section('title', 'Agung Motor — Perawatan Otomotif Premium')

@section('content')

  {{-- ===== HERO SCENE ===== --}}
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-main">
    {{-- Dynamic Background Layer --}}
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-main/50 to-main z-10"></div>
      <div class="absolute inset-0 hero-bg opacity-40 scale-110" id="hero-image"></div>
      <div class="absolute inset-0 radial-glow opacity-30"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-20 text-center space-y-12">
      <div class="space-y-6" id="hero-main-content">
        <span
          class="inline-block px-6 py-2 rounded-full glass-dark text-brand-primary text-[10px] font-black uppercase tracking-[0.4em] animate-pulse">
          Presisi Tanpa Batas
        </span>
        <h1 class="text-6xl md:text-9xl font-black leading-none tracking-tighter text-main reveal-text">
          PRESISI & <br>
          <span class="text-brand-primary text-glow">PERFORMA.</span>
        </h1>
        <p class="text-muted text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">
          Rasakan puncak perawatan sepeda motor. Di mana keahlian teknik bertemu dengan perhatian detail yang sempurna untuk performa mutlak.
        </p>
      </div>

      <div class="flex flex-wrap items-center justify-center gap-6" id="hero-actions">
        <a href="https://wa.me/6281234567890"
          class="px-10 py-5 bg-brand-primary text-white font-black text-xs uppercase tracking-widest rounded-full hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-brand-primary/30 flex items-center gap-3">
          <i class="fa-brands fa-whatsapp text-xl"></i>
          Booking Konsultasi
        </a>
        <a href="#services"
          class="px-10 py-5 glass-dark text-main font-black text-xs uppercase tracking-widest rounded-full hover:bg-main/5 transition-all flex items-center gap-3 group">
          Jelajahi Layanan
          <i class="fa-solid fa-arrow-down text-[10px] group-hover:translate-y-1 transition-transform"></i>
        </a>
      </div>
    </div>

    {{-- Bottom Fade --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3 opacity-30">
      <span class="text-[8px] font-black uppercase tracking-[0.5em] text-muted">Gulir untuk Menjelajahi</span>
      <div class="w-[1px] h-20 bg-gradient-to-b from-brand-primary to-transparent"></div>
    </div>
  </section>

  {{-- ===== BRAND MARQUEE ===== --}}
  <section class="py-20 bg-surface border-y border-brand-primary/5 overflow-hidden">
    <div class="mask-fade overflow-hidden">
      <div class="flex items-center gap-20 animate-marquee whitespace-nowrap">
        @foreach (['MOTUL', 'MOBIL 1', 'ENEOS', 'SHELL', 'CASTROL', 'HONDA', 'YAMAHA', 'SUZUKI', 'KAWASAKI', 'NGK', 'BOSCH'] as $brand)
          <span class="text-5xl md:text-7xl font-black text-main/10 hover:text-brand-primary/20 transition-colors cursor-default tracking-tighter">{{ $brand }}</span>
        @endforeach
        {{-- Duplicate --}}
        @foreach (['MOTUL', 'MOBIL 1', 'ENEOS', 'SHELL', 'CASTROL', 'HONDA', 'YAMAHA', 'SUZUKI', 'KAWASAKI', 'NGK', 'BOSCH'] as $brand)
          <span class="text-5xl md:text-7xl font-black text-main/10 hover:text-brand-primary/20 transition-colors cursor-default tracking-tighter">{{ $brand }}</span>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ===== CINEMATIC SERVICE SECTION ===== --}}
  <section id="services" class="py-20 md:py-40 bg-main relative">
    <div class="absolute top-0 right-0 w-1/2 h-full radial-glow opacity-10"></div>
    
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-20 items-end mb-32">
        <div class="space-y-6">
          <span class="text-brand-primary text-[10px] font-black uppercase tracking-[0.5em]">Keahlian Kami</span>
          <h2 class="text-5xl md:text-7xl font-black leading-tight reveal-text">
            MENGUASAI SENI <br>
            <span class="text-main/20">PERAWATAN.</span>
          </h2>
        </div>
        <p class="text-muted text-xl max-w-md pb-4 font-medium border-l-2 border-brand-primary pl-8">
          Bengkel kami bukan sekadar garasi; ini adalah tempat perlindungan bagi para antusias yang menuntut kesempurnaan di setiap putaran mesin.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-1px bg-brand-primary/10 border border-brand-primary/10 overflow-hidden rounded-3xl" id="service-grid">
        @foreach ([
          ['01', 'Service Rutin', 'Perawatan mendasar untuk umur panjang dan efisiensi puncak mesin Anda.', 'fa-screwdriver-wrench'],
          ['02', 'Overhaul Mesin', 'Restorasi mendalam untuk mengembalikan performa standar pabrik.', 'fa-gear'],
          ['03', 'Kelistrikan', 'Diagnosa canggih dan teknik kelistrikan dengan presisi tinggi.', 'fa-bolt'],
          ['04', 'Sistem Rem', 'Keamanan tanpa kompromi melalui keunggulan teknis dan material.', 'fa-shield-halved'],
          ['05', 'Suspensi', 'Menyempurnakan hubungan antara Anda dan jalanan untuk kenyamanan.', 'fa-arrows-up-down'],
          ['06', 'Restorasi', 'Menghidupkan kembali warisan motor Anda dengan presisi modern.', 'fa-spray-can-sparkles']
        ] as [$num, $title, $desc, $icon])
          <div class="bg-surface p-12 hover:bg-brand-primary/5 transition-colors group relative overflow-hidden glass-card">
            <span class="text-4xl font-black text-main/5 absolute -top-4 -right-4 group-hover:text-brand-primary/10 transition-colors">{{ $num }}</span>
            <i class="fa-solid {{ $icon }} text-3xl text-brand-primary mb-8"></i>
            <h3 class="text-2xl font-bold mb-4 text-main">{{ $title }}</h3>
            <p class="text-muted text-sm leading-relaxed mb-8">{{ $desc }}</p>
            <a href="{{ route('service') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-brand-primary group-hover:gap-4 transition-all">
              Lihat Detail <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ===== PARALLAX FEATURE SECTION ===== --}}
  <section class="py-20 md:py-40 bg-main overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center">
      <div class="relative group" id="parallax-container">
        <div class="aspect-[4/5] rounded-3xl overflow-hidden glass-dark neon-border">
          <img src="/images/about.png" alt="Detailing" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-1000" id="parallax-img">
        </div>
        <div class="absolute -bottom-10 -right-10 glass-dark p-10 rounded-3xl border-l-4 border-brand-primary max-w-[280px] hidden md:block">
          <p class="text-xs font-bold text-muted mb-2 tracking-widest uppercase">Sejak 2009</p>
          <p class="text-sm font-medium leading-relaxed italic text-main">"Kami percaya setiap baut memiliki cerita, dan setiap suara mesin adalah simfoni."</p>
        </div>
      </div>

      <div class="space-y-10">
        <div class="space-y-6">
          <span class="text-brand-primary text-[10px] font-black uppercase tracking-[0.5em]">Warisan</span>
          <h2 class="text-5xl md:text-6xl font-black text-main reveal-text">DI MANA JIWA BERTEMU <span class="text-brand-primary">BAJA.</span></h2>
        </div>
        <p class="text-muted text-lg leading-relaxed font-medium">
          Lebih dari sekadar perbaikan, kami memberikan jiwa kembali pada kendaraan Anda. Menggunakan teknologi diagnosa terbaru yang dipadukan dengan pengalaman profesional selama belasan tahun.
        </p>
        <div class="grid grid-cols-2 gap-12 pt-8">
          <div>
            <p class="text-4xl font-black text-main mb-2">15+</p>
            <p class="text-[10px] text-muted uppercase font-black tracking-widest">Tahun Berpengalaman</p>
          </div>
          <div>
            <p class="text-4xl font-black text-main mb-2">12k+</p>
            <p class="text-[10px] text-muted uppercase font-black tracking-widest">Pengendara Puas</p>
          </div>
        </div>
        <div class="pt-10">
          <a href="{{ route('about') }}" class="inline-flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-full border border-brand-primary/10 flex items-center justify-center group-hover:bg-brand-primary group-hover:border-brand-primary transition-all">
              <i class="fa-solid fa-arrow-right text-main"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest text-main">Filosofi Kami</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- ===== CINEMATIC CTA ===== --}}
  <section class="py-32 md:py-60 relative flex items-center justify-center overflow-hidden bg-main">
    <div class="absolute inset-0 z-0">
       <div class="absolute inset-0 bg-main/80 z-10"></div>
       <div class="absolute inset-0 hero-bg opacity-30 fixed"></div>
    </div>

    <div class="max-w-4xl mx-auto px-6 text-center relative z-20 space-y-12">
      <h2 class="text-5xl md:text-8xl font-black leading-none tracking-tighter text-main reveal-text">
        SIAP UNTUK <br>
        <span class="text-brand-primary text-glow">MELAJU?</span>
      </h2>
      <p class="text-muted text-lg md:text-xl max-w-xl mx-auto font-medium">
        Ambil langkah pertama menuju performa mutlak. Biarkan ahli kami meningkatkan pengalaman berkendara Anda ke level berikutnya.
      </p>
      <div class="pt-8">
        <a href="https://wa.me/6281234567890" class="btn-whatsapp px-12 py-6 text-sm">
          <i class="fa-brands fa-whatsapp text-2xl"></i>
          AMANKAN SESI ANDA
        </a>
      </div>
    </div>
  </section>

@endsection

@push('styles')
  @vite(['resources/css/landing.css'])
@endpush

@push('scripts')
  @vite(['resources/js/animations.js'])
@endpush
