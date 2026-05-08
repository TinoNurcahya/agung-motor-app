@extends('layouts.app')

@section('title', 'Tentang Kami — Agung Motor')

@section('content')

  <!-- ===== PAGE HERO ===== -->
  <section class="relative pt-40 pb-20 bg-brand-dark overflow-hidden">
    <div class="absolute inset-0 radial-glow pointer-events-none opacity-60"></div>
    <div class="max-w-7xl mx-auto px-6 text-center space-y-4 relative z-10">
      <span
        class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest">Tentang
        Kami</span>
      <h1 class="text-5xl md:text-7xl font-extrabold">Ahlinya <span class="text-brand-primary">Motor</span> Anda</h1>
      <p class="text-gray-500 max-w-2xl mx-auto text-lg">Bekerja yang terbaik untuk motor Anda dan bekerja sepenuh hati
        demi kepuasan Anda.</p>
    </div>
  </section>

  <!-- ===== ABOUT CONTENT ===== -->
  <section class="py-24 bg-brand-dark">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
      <div class="space-y-8">
        <h2 class="text-4xl font-bold leading-tight uppercase">Filosofi <span class="text-brand-primary">Agung
            Motor</span></h2>
        <p class="text-gray-500 leading-relaxed italic border-l-4 border-brand-primary pl-4 py-1">"Bekerja yang terbaik
          untuk motor Anda dan bekerja sepenuh hati demi Anda."</p>
        <p class="text-gray-500 leading-relaxed">
          Ini merupakan filosofi dan cara kerja kami di Bengkel Agung Motor yang bertujuan untuk memuaskan setiap
          pelanggan kami dengan produk pilihan dan layanan terbaik untuk Anda dan juga motor Anda. Percaya pada setiap
          layanan yang diberikan oleh Bengkel Agung Motor.
        </p>
        <p class="text-gray-500 leading-relaxed">
          Bengkel kami ahli dalam layanan mesin, rem, aki, shock, oli dan sebagainya. Kami menawarkan semua layanan dengan
          mekanik yang terlatih secara professional dalam bidangnya masing-masing. Mekanik kami siap memberikan saran dan
          layanan yang maksimal menggunakan peralatan serta teknologi terbaik yang pastinya selalu terupdate dengan suku
          cadang berkualitas yang harganya transparan.
        </p>

        <div class="grid grid-cols-3 gap-6 pt-4">
          <div class="text-center glass-card p-6">
            <p class="text-4xl font-extrabold text-brand-primary">15+</p>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Tahun Berpengalaman</p>
          </div>
          <div class="text-center glass-card p-6">
            <p class="text-4xl font-extrabold text-brand-income">5k+</p>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Pelanggan Puas</p>
          </div>
          <div class="text-center glass-card p-6">
            <p class="text-4xl font-extrabold text-white">12</p>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Mekanik Ahli</p>
          </div>
        </div>
      </div>

      <div class="glass-card aspect-video flex items-center justify-center text-gray-700 text-sm italic">
        [ About Image / Workshop Photo ]
      </div>
    </div>
  </section>

  <!-- ===== TEAM STRIP ===== -->
  <section class="py-20 bg-brand-surface">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-12 space-y-3">
        <h2 class="text-3xl md:text-4xl font-bold">Tim <span class="text-brand-primary">Mekanik</span> Kami</h2>
        <p class="text-gray-500">Bersertifikat, berpengalaman, dan berdedikasi.</p>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ([['Agung Prayitno', 'Kepala Mekanik', 'fa-crown'], ['Deni Kurniawan', 'Mekanik Senior', 'fa-star'], ['Rizky Fauzan', 'Spesialis Injeksi', 'fa-microchip'], ['Bayu Saputra', 'Mekanik Kelistrikan', 'fa-bolt']] as [$name, $role, $icon])
          <div class="glass-card p-6 text-center group">
            <div
              class="w-20 h-20 mx-auto rounded-full bg-brand-primary/10 flex items-center justify-center mb-4 group-hover:bg-brand-primary transition-colors duration-300">
              <i class="fa-solid {{ $icon }} text-2xl text-brand-primary group-hover:text-white"></i>
            </div>
            <h4 class="font-bold">{{ $name }}</h4>
            <p class="text-xs text-gray-500 mt-1">{{ $role }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

@endsection
