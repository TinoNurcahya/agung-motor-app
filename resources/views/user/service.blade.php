@extends('layouts.app')

@section('title', 'Layanan Servis — Agung Motor')

@section('content')

  <!-- ===== PAGE HERO ===== -->
  <section class="relative pt-40 pb-20 bg-brand-dark overflow-hidden">
    <div class="absolute inset-0 radial-glow pointer-events-none opacity-60"></div>
    <div class="max-w-7xl mx-auto px-6 text-center space-y-4 relative z-10">
      <span
        class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest">
        Layanan Kami
      </span>
      <h1 class="text-5xl md:text-7xl font-extrabold">Semua Kebutuhan <br><span class="text-brand-primary">Motor</span> Anda
      </h1>
      <p class="text-gray-500 max-w-2xl mx-auto text-lg">Dari servis ringan hingga overhaul mesin, mekanik profesional kami
        siap membantu.</p>
    </div>
  </section>

  <!-- ===== SERVICES GRID ===== -->
  <section class="py-24 bg-brand-dark">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Service Card 1 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-screwdriver-wrench text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Service Rutin</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Ganti oli, filter udara, busi, dan pengecekan seluruh
            komponen vital motor Anda.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 75.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Service%20Rutin"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

        <!-- Service Card 2 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-gear text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Overhaul Mesin</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Bongkar total mesin, ganti ring piston, seal klep, dan
            pembersihan karburator/injeksi.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 500.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Overhaul%20Mesin"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

        <!-- Service Card 3 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-bolt text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Servis Kelistrikan</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Perbaikan sistem pengapian, ganti aki, klakson, lampu LED,
            dan instalasi aksesoris.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 100.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Servis%20Kelistrikan"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

        <!-- Service Card 4 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-circle-notch text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Ganti Ban</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Ganti ban depan/belakang, spooring, balancing, dan tambal
            ban tubeless.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 50.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Ganti%20Ban"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

        <!-- Service Card 5 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-droplet text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Servis Rem</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Penggantian kampas rem, minyak rem, kalibrasi
            tromol/cakram untuk keselamatan berkendara.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 80.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Servis%20Rem"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

        <!-- Service Card 6 -->
        <article class="glass-card p-8 group">
          <div
            class="w-16 h-16 rounded-2xl bg-brand-primary/10 flex items-center justify-center mb-6 group-hover:bg-brand-primary transition-colors duration-300">
            <i class="fa-solid fa-spray-can-sparkles text-2xl text-brand-primary group-hover:text-white"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Cat & Restorasi</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Pengecatan bodi motor, perbaikan baret, poles bodi, dan
            restorasi tampilan keseluruhan.</p>
          <p class="text-brand-primary font-bold text-lg mb-6">Mulai Rp 300.000</p>
          <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20Cat%20dan%20Restorasi"
            class="btn-whatsapp py-2 px-5 text-sm rounded-xl">
            <i class="fa-brands fa-whatsapp"></i> Booking Sekarang
          </a>
        </article>

      </div>
    </div>
  </section>

@endsection
