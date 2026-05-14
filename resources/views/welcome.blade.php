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

  {{-- ===== PROFESSIONAL FAQ (DICODING STYLE) ===== --}}
  <section class="py-20 md:py-32 bg-main border-t border-brand-primary/10 relative overflow-hidden" x-data="{ activeTab: 'all', activeAccordion: null }">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[400px] radial-glow opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
      {{-- Header FAQ --}}
      <div class="text-center space-y-4 mb-12">
        <span class="text-brand-primary text-[10px] font-black uppercase tracking-[0.5em] px-4 py-1.5 rounded-full bg-brand-primary/10 border border-brand-primary/20 shadow-sm inline-block">
          Pertanyaan Populer
        </span>
        <h2 class="text-4xl md:text-6xl font-black tracking-tight text-main reveal-text">
          PERTANYAAN UMUM <br>
          <span class="text-brand-primary text-glow">(FAQ)</span>
        </h2>
        <p class="text-muted text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed">
          Temukan jawaban atas pertanyaan seputar garansi, keaslian suku cadang, dan prosedur pendaftaran servis di Agung Motor.
        </p>
      </div>

      {{-- Kategori Tab Filters (Sleek Floating Pills) --}}
      <div class="flex flex-wrap items-center justify-center gap-3 max-w-3xl mx-auto mb-12">
        @foreach ([
          ['all', 'Semua Pertanyaan', 'fa-asterisk'],
          ['garansi', 'Servis & Garansi', 'fa-shield-halved'],
          ['stok', 'Sparepart & Stok', 'fa-box'],
          ['biaya', 'Booking & Biaya', 'fa-wallet']
        ] as [$id, $title, $icon])
          <button @click="activeTab = '{{ $id }}'; activeAccordion = null"
            :class="activeTab === '{{ $id }}' ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/30 font-bold scale-105' : 'bg-surface text-muted hover:text-main border border-brand-primary/10 hover:border-brand-primary/30'"
            class="px-6 py-3 rounded-full text-xs font-semibold flex items-center gap-2 transition-all duration-300 shadow-sm">
            <i class="fa-solid {{ $icon }} text-sm"></i>
            {{ $title }}
          </button>
        @endforeach
      </div>

      {{-- Accordion Daftar Pertanyaan (Seamless Single List) --}}
      <div class="max-w-3xl mx-auto bg-surface border border-brand-primary/10 rounded-2xl glass divide-y divide-brand-primary/10 shadow-lg overflow-hidden">
        @php
          $faqs = [
            [
              'cat' => 'garansi',
              'q' => 'Apakah Agung Motor memberikan garansi untuk setiap pengerjaan servis?',
              'a' => 'Tentu. Setiap servis rutin maupun overhaul mesin yang dikerjakan oleh mekanik kami disertai dengan Garansi Servis resmi selama 14 hari atau 1.000 km (mana yang tercapai lebih dulu). Jika keluhan yang sama muncul kembali, kami akan melakukan perbaikan tanpa dikenakan biaya jasa.'
            ],
            [
              'cat' => 'stok',
              'q' => 'Bagaimana cara menjamin keaslian suku cadang (sparepart) yang dipasang?',
              'a' => 'Kami menjalin kerja sama langsung dengan distributor resmi dari berbagai merek terkemuka seperti Motul, Shell, Yamalube, AHM, dan Bosch. Seluruh persediaan yang tersimpan di gudang kami dijamin 100% orisinal dan telah melalui verifikasi nomor seri pabrikan.'
            ],
            [
              'cat' => 'biaya',
              'q' => 'Apakah saya wajib melakukan booking jadwal atau bisa langsung datang ke bengkel?',
              'a' => 'Anda dapat langsung datang (go-show) ke bengkel kami pada jam operasional. Namun, untuk menghindari antrean panjang pada akhir pekan atau jam sibuk, kami sangat menyarankan Anda melakukan konsultasi dan booking jadwal secara gratis melalui layanan WhatsApp kami.'
            ],
            [
              'cat' => 'biaya',
              'q' => 'Bagaimana sistem penghitungan dan transparansi estimasi biaya servis di Agung Motor?',
              'a' => 'Transparansi adalah prioritas kami. Sebelum mekanik melakukan pembongkaran atau penggantian suku cadang, kasir/mekanik akan memberikan rincian estimasi biaya jasa dan sparepart secara tertulis. Pengerjaan baru akan dimulai setelah mendapatkan persetujuan penuh dari Anda.'
            ],
            [
              'cat' => 'garansi',
              'q' => 'Apakah Agung Motor melayani perbaikan darurat atau modifikasi kelistrikan canggih?',
              'a' => 'Ya, mekanik spesialis kelistrikan kami terlatih untuk mendiagnosis masalah sistem injeksi (ECU), pengereman ABS, serta perbaikan darurat kelistrikan. Kami menggunakan pemindai diagnostik (scanner) mutakhir yang kompatibel dengan berbagai merek motor terkini.'
            ],
          ];
        @endphp

        @foreach ($faqs as $index => $faq)
          <div x-show="activeTab === 'all' || activeTab === '{{ $faq['cat'] }}'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-1"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="transition-colors duration-300">
            {{-- Pertanyaan --}}
            <button @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
              class="w-full px-8 py-6 text-left flex items-center justify-between gap-6 hover:bg-brand-primary/5 transition-colors group">
              <span :class="activeAccordion === {{ $index }} ? 'text-brand-primary' : 'text-main group-hover:text-brand-primary'"
                class="font-extrabold text-base md:text-lg transition-colors pr-4">
                {{ $faq['q'] }}
              </span>
              <div :class="activeAccordion === {{ $index }} ? 'rotate-180 bg-brand-primary text-white border-brand-primary' : 'bg-surface border border-brand-primary/20 text-muted'"
                class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-transform duration-300 shadow-sm">
                <i class="fa-solid fa-chevron-down text-sm"></i>
              </div>
            </button>

            {{-- Jawaban --}}
            <div x-show="activeAccordion === {{ $index }}"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              class="px-8 pb-7 pt-2 text-muted text-sm md:text-base leading-relaxed bg-brand-surface/20">
              <p>{{ $faq['a'] }}</p>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Kotak Kontak Khusus FAQ (Sleek Callout Banner dengan Jarak Lega mt-20) --}}
      <div class="mt-20 p-10 rounded-3xl border border-brand-primary/20 bg-gradient-to-b from-brand-primary/5 to-transparent text-center space-y-6 max-w-3xl mx-auto shadow-sm">
        <div class="inline-flex p-4 rounded-2xl bg-brand-primary/10 text-brand-primary mb-2">
          <i class="fa-solid fa-headset text-3xl animate-pulse"></i>
        </div>
        <div class="space-y-2 font-medium">
          <h4 class="text-2xl font-extrabold text-main tracking-tight">Punya Pertanyaan Spesifik Lainnya?</h4>
          <p class="text-sm text-muted max-w-lg mx-auto leading-relaxed">
            Tim customer service dan mekanik kepala kami siap memberikan konsultasi teknis mendalam mengenai kondisi sepeda motor Anda secara gratis.
          </p>
        </div>
        <div class="pt-2">
          <a href="https://wa.me/6281234567890" class="btn-whatsapp py-4 px-10 text-sm font-bold shadow-lg shadow-brand-whatsapp/20 hover:scale-105 transition-transform">
            <i class="fa-brands fa-whatsapp text-xl"></i> Tanyakan via WhatsApp
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
