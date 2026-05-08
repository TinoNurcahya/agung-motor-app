@extends('layouts.app')

@section('title', 'Agung Motor — Bengkel Motor Modern & Terpercaya')

@section('content')

  {{-- ===== HERO SECTION START ===== --}}
  <section id="home" class="relative min-h-screen flex items-center hero-bg pt-20">
    <div class="absolute inset-0 radial-glow pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 py-24">
      <div class="max-w-2xl space-y-8">
        <span
          class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest border border-brand-primary/20">
          Bengkel Motor Professional
        </span>
        <h1 class="text-5xl md:text-7xl font-extrabold leading-[1.1] tracking-tight text-white">
          Rawat Motormu <br>
          Dengan <span class="text-brand-primary">Presisi</span> Tinggi.
        </h1>
        <p class="text-gray-500 text-lg max-w-lg leading-relaxed">
          Layanan servis motor terbaik dengan mekanik berpengalaman, suku cadang original, dan teknologi diagnosa modern.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+servis+motor" class="btn-whatsapp">
            <i class="fa-brands fa-whatsapp text-xl"></i>
            Konsultasi via WhatsApp
          </a>
          <a href="{{ route('service') }}"
            class="glass px-8 py-3 rounded-full font-semibold hover:bg-white/10 transition-all inline-flex items-center gap-2 text-dark hover:text-white">
            Lihat Layanan <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
  {{-- ===== HERO SECTION END ===== --}}

  {{-- ===== STATS STRIP START ===== --}}
  <section class="py-12 bg-brand-surface border-y border-white/5">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach ([['15+', 'Tahun Pengalaman'], ['10k+', 'Pelanggan Puas'], ['12+', 'Mekanik Ahli'], ['100%', 'Garansi Servis']] as [$val, $label])
        <div class="text-center">
          <p class="text-3xl md:text-4xl font-black text-brand-primary mb-1">{{ $val }}</p>
          <p class="text-xs uppercase tracking-widest text-muted font-bold">{{ $label }}</p>
        </div>
      @endforeach
    </div>
  </section>
  {{-- ===== STATS STRIP END ===== --}}

  {{-- ===== LOGO MARQUEE START ===== --}}
  <section class="py-12 bg-brand-surface/30 border-y border-brand-primary/5 overflow-hidden relative">
    <div class="flex items-center gap-4 mb-4 px-6 max-w-7xl mx-auto">
      <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
      <span class="text-[10px] font-black uppercase tracking-[0.3em] text-muted">Official Partners & Brands</span>
    </div>

    <div class="relative flex overflow-x-hidden group">
      <div class="animate-marquee flex items-center gap-16 whitespace-nowrap py-4">
        @foreach ([['MOBIL 1', '#B33232'], ['MOTUL', '#FFFFFF'], ['ENEOS', '#EAB308'], ['SHELL', '#EAB308'], ['CASTROL', '#10B981'], ['YAMALUBE', '#B33232'], ['SUZUKI', '#FFFFFF'], ['HONDA', '#B33232'], ['KAWASAKI', '#10B981']] as [$brand, $color])
          <div class="flex items-center gap-3">
            <span class="text-2xl md:text-3xl font-black italic tracking-tighter"
              style="color: {{ $color }}; opacity: 0.6;">{{ $brand }}</span>
          </div>
        @endforeach
        {{-- Duplicate for seamless loop --}}
        @foreach ([['MOBIL 1', '#B33232'], ['MOTUL', '#FFFFFF'], ['ENEOS', '#EAB308'], ['SHELL', '#EAB308'], ['CASTROL', '#10B981'], ['YAMALUBE', '#B33232'], ['SUZUKI', '#FFFFFF'], ['HONDA', '#B33232'], ['KAWASAKI', '#10B981']] as [$brand, $color])
          <div class="flex items-center gap-3">
            <span class="text-2xl md:text-3xl font-black italic tracking-tighter"
              style="color: {{ $color }}; opacity: 0.6;">{{ $brand }}</span>
          </div>
        @endforeach
      </div>

      {{-- Gradient Overlays --}}
      <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-brand-main to-transparent z-10"></div>
      <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-brand-main to-transparent z-10"></div>
    </div>
  </section>

  <style>
    @keyframes marquee {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    .animate-marquee {
      animation: marquee 30s linear infinite;
    }

    .animate-marquee:hover {
      animation-play-state: paused;
    }
  </style>
  {{-- ===== LOGO MARQUEE END ===== --}}

  {{-- ===== SERVICES SECTION START ===== --}}
  <section id="services" class="py-24 bg-brand-dark">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16 space-y-4">
        <span
          class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest">Layanan</span>
        <h2 class="text-3xl md:text-5xl font-bold">Layanan <span class="text-brand-primary">Kami</span></h2>
        <p class="text-gray-500 max-w-2xl mx-auto">Kami menyediakan berbagai layanan perawatan motor untuk memastikan
          performa kendaraan Anda tetap optimal.</p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ([['fa-screwdriver-wrench', 'Service Rutin', 'Ganti oli, filter, busi, dan pengecekan seluruh komponen vital motor Anda.'], ['fa-gear', 'Overhaul Mesin', 'Bongkar total mesin, ring piston, dan pembersihan karburator / sistem injeksi.'], ['fa-bolt', 'Kelistrikan', 'Perbaikan pengapian, ganti aki, lampu LED, dan instalasi aksesoris elektronik.'], ['fa-circle-notch', 'Ganti Ban', 'Ganti ban depan/belakang, spooring, balancing, dan tambal ban tubeless.'], ['fa-droplet', 'Servis Rem', 'Penggantian kampas rem, minyak rem, kalibrasi tromol/cakram.'], ['fa-spray-can-sparkles', 'Cat & Restorasi', 'Pengecatan bodi, perbaikan baret, poles, dan restorasi tampilan keseluruhan.']] as [$icon, $title, $desc])
          <article class="glass-card p-8 group">
            <div
              class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
              <i
                class="fa-solid {{ $icon }} text-2xl text-brand-primary group-hover:text-white transition-colors duration-300"></i>
            </div>
            <h3 class="text-xl font-bold mb-3">{{ $title }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ $desc }}</p>
            <a href="{{ route('service') }}"
              class="text-brand-primary text-sm font-bold inline-flex items-center gap-2 hover:gap-3 transition-all">
              Detail Layanan <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>
  {{-- ===== SERVICES SECTION END ===== --}}

  {{-- ===== CTA SECTION START ===== --}}
  <section class="py-24 bg-brand-main relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-primary/10 rounded-full blur-[120px]"></div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
      <h2 class="text-4xl md:text-5xl font-black mb-8 leading-tight">Siap Memberikan Performa Terbaik Untuk Motormu?</h2>
      <p class="text-gray-500 text-lg mb-10">Konsultasikan keluhan motormu sekarang secara gratis via WhatsApp.</p>
      <a href="https://wa.me/6281234567890" class="btn-whatsapp py-4 px-10 text-lg">
        <i class="fa-brands fa-whatsapp text-2xl"></i> Hubungi Kami Sekarang
      </a>
    </div>
  </section>
  {{-- ===== CTA SECTION END ===== --}}

@endsection
