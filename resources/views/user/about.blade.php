@extends('layouts.app')

@section('title', 'Filosofi Agung Motor — Bengkel Premium')

@section('content')

  <!-- ===== CINEMATIC HERO ===== -->
  <section class="relative min-h-[90vh] flex items-center justify-center bg-main overflow-hidden">
    <!-- Parallax BG (Handled in CSS) -->
    <div class="absolute inset-0 about-hero-bg z-0 opacity-40 mix-blend-luminosity"></div>
    <!-- Red Neon Glow Blur -->
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-brand-primary/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-red-900/10 rounded-full blur-[100px] pointer-events-none z-0"></div>
    
    <div class="max-w-7xl mx-auto px-6 text-center space-y-6 relative z-10 w-full flex flex-col items-center">
      <span class="inline-block px-5 py-2 rounded-full glass border border-brand-primary/20 text-brand-primary text-xs font-black uppercase tracking-[0.3em] mb-4">
        Warisan & Performa
      </span>
      <h1 id="hero-title" class="text-6xl md:text-8xl lg:text-9xl font-black text-main leading-none tracking-tighter">
        SENI <br/> <span class="text-stroke-primary">OTOMOTIF</span>
      </h1>
      <p id="hero-subtitle" class="text-muted max-w-2xl mx-auto text-sm md:text-base tracking-[0.2em] uppercase font-semibold mt-8">
        Lebih dari sekadar perbaikan. Kami merestorasi jiwa kendaraan Anda dengan presisi tanpa batas.
      </p>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 opacity-50">
      <span class="text-[9px] uppercase tracking-[0.4em] font-bold text-main">Temukan Filosofi</span>
      <div class="glow-line h-16 w-[1px] bg-gradient-to-b from-brand-primary to-transparent"></div>
    </div>
  </section>

  <!-- ===== THE PHILOSOPHY (STORYTELLING) ===== -->
  <section class="py-20 md:py-32 bg-main relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-16 items-center">
      
      <!-- Text Content -->
      <div class="lg:col-span-5 space-y-10">
        <h2 class="text-4xl md:text-5xl font-black text-main leading-tight story-reveal">
          DI MANA <span class="text-brand-primary italic">JIWA</span> BERTEMU BAJA
        </h2>
        <div class="space-y-6 text-muted text-lg leading-relaxed story-reveal font-light">
          <p>
            Berdiri sejak lebih dari 15 tahun lalu, Agung Motor lahir dari obsesi mendalam terhadap mesin roda dua. Kami percaya bahwa setiap motor memiliki karakter dan cerita unik yang patut dihargai.
          </p>
          <p>
            Bukan sekadar mengganti suku cadang, filosofi kami adalah tentang "Presisi dan Perhatian". Mekanik kami tidak sekadar memperbaiki, mereka menyembuhkan. Kami menggunakan perangkat analitik modern dikombinasikan dengan sentuhan tangan profesional yang tak tergantikan oleh mesin.
          </p>
        </div>
        
        <div class="story-reveal pt-4 flex gap-8 border-t border-brand-primary/10">
          <div>
            <p class="text-4xl font-black text-main">15+</p>
            <p class="text-xs tracking-widest text-muted uppercase mt-2 font-bold">Tahun Keunggulan</p>
          </div>
          <div>
            <p class="text-4xl font-black text-main">5K+</p>
            <p class="text-xs tracking-widest text-muted uppercase mt-2 font-bold">Motor Terselamatkan</p>
          </div>
        </div>
      </div>

      <!-- Parallax Image -->
      <div class="lg:col-span-7 relative story-reveal">
        <div class="parallax-wrap aspect-[4/3] glass border border-brand-primary/10 shadow-2xl relative">
          <!-- Subtle Red overlay -->
          <div class="absolute inset-0 bg-brand-primary/5 mix-blend-overlay z-10 pointer-events-none"></div>
          <img src="/images/about.png" alt="Interior Bengkel Agung Motor" class="parallax-inner w-full h-full object-cover">
        </div>
        <!-- Floating Stat Element -->
        <div class="absolute -bottom-10 -left-10 glass-card p-6 md:p-8 max-w-xs z-20 border-l-4 border-l-brand-primary shadow-2xl hidden md:block">
          <p class="text-sm font-bold text-main">Standar Kualitas Tertinggi</p>
          <p class="text-xs text-muted mt-2">100% Suku Cadang Original & Bergaransi</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ===== CINEMATIC QUOTE ===== -->
  <section class="py-20 md:py-40 bg-surface-solid relative border-y border-brand-primary/5">
    <div class="absolute inset-0 bg-[url('/images/hero.png')] opacity-[0.02] mix-blend-luminosity"></div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10 story-reveal">
      <span class="quote-mark font-serif">"</span>
      <h3 class="text-3xl md:text-5xl font-light text-main leading-snug relative z-10 italic">
        Setiap baut yang kami kencangkan, adalah <span class="font-bold text-brand-primary">janji keamanan</span> yang kami berikan kepada setiap pengendara.
      </h3>
      <div class="mt-12 flex flex-col items-center gap-3">
        <div class="w-12 h-1 bg-brand-primary"></div>
        <p class="text-main font-bold tracking-widest uppercase text-sm mt-4">Agung Prayitno</p>
        <p class="text-muted text-xs tracking-widest uppercase">Pendiri & Master Mekanik</p>
      </div>
    </div>
  </section>

  <!-- ===== TEAM SECTION ===== -->
  <section class="py-20 md:py-32 bg-main relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8 story-reveal">
        <div class="max-w-2xl">
          <span class="text-brand-primary text-xs font-black uppercase tracking-[0.2em] mb-3 block">Arsitek Performa</span>
          <h2 class="text-4xl md:text-6xl font-black text-main leading-tight">MAESTRO <br/>DI BALIK <span class="text-brand-primary italic">MESIN</span></h2>
        </div>
        <p class="text-muted max-w-sm text-sm leading-relaxed border-l border-brand-primary/30 pl-4 py-2">
          Hanya talenta terbaik dengan pengalaman puluhan tahun yang kami percayakan untuk menyentuh kendaraan Anda.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6" id="team-container">
        @foreach ([
          ['Agung Prayitno', 'Master Mekanik & CEO', 'fa-crown'], 
          ['Deni Kurniawan', 'Kepala Divisi Mesin', 'fa-gear'], 
          ['Rizky Fauzan', 'Pakar Diagnostik ECU', 'fa-microchip'], 
          ['Bayu Saputra', 'Spesialis Kinerja Rem', 'fa-shield-halved']
        ] as [$name, $role, $icon])
          <article class="team-card glass p-8 rounded-3xl hover:bg-brand-surface border border-brand-primary/5 hover:border-brand-primary/30 transition-all duration-500 group relative overflow-hidden">
            <!-- Glow effect on hover -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-primary/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            
            <div class="w-16 h-16 rounded-2xl bg-brand-surface-solid border border-brand-primary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500 relative z-10 shadow-lg">
              <i class="fa-solid {{ $icon }} text-2xl text-main group-hover:text-brand-primary transition-colors"></i>
            </div>
            
            <div class="relative z-10">
              <h4 class="font-black text-xl text-main mb-2">{{ $name }}</h4>
              <p class="text-xs text-brand-primary font-bold uppercase tracking-widest">{{ $role }}</p>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ===== CTA SECTION ===== -->
  <section class="py-20 md:py-32 bg-main relative overflow-hidden">
    <div class="absolute inset-0 bg-brand-surface-solid border-t border-brand-primary/10"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-64 bg-brand-primary/20 blur-[120px] rounded-t-full pointer-events-none"></div>
    
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10 story-reveal">
      <h2 class="text-4xl md:text-6xl font-black text-main mb-8">TINGKATKAN STANDAR <br/> KENDARAAN ANDA</h2>
      <p class="text-muted text-lg mb-12 max-w-xl mx-auto">Jadwalkan kunjungan Anda dan rasakan perbedaan servis kelas dunia dari mekanik elit kami.</p>
      
      <a href="/contact" class="inline-flex items-center gap-4 bg-brand-primary text-white px-10 py-5 rounded-full font-bold uppercase tracking-widest hover:bg-white hover:text-brand-primary transition-all duration-300 shadow-[0_0_40px_rgba(179,50,50,0.4)] group">
        <span>Eksklusif Booking</span>
        <i class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300"></i>
      </a>
    </div>
  </section>

@endsection

@push('styles')
  @vite(['resources/css/about.css'])
@endpush

@push('scripts')
  @vite(['resources/js/about.js'])
@endpush
