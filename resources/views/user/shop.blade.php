@extends('layouts.app')

@section('title', 'Toko Spare Part — Agung Motor')

@section('content')

  <!-- ===== PAGE HERO ===== -->
  <section class="relative pt-40 pb-20 bg-brand-dark overflow-hidden">
    <div class="absolute inset-0 radial-glow pointer-events-none opacity-60"></div>
    <div class="max-w-7xl mx-auto px-6 text-center space-y-4 relative z-10">
      <span
        class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest">Spare
        Part Original</span>
      <h1 class="text-5xl md:text-7xl font-extrabold">Toko <span class="text-brand-primary">Spare Part</span></h1>
      <p class="text-gray-500 max-w-2xl mx-auto text-lg">Suku cadang original bergaransi. Order sekarang, siap pasang di
        bengkel kami.</p>
    </div>
  </section>

  <!-- ===== SHOP GRID ===== -->
  <section class="py-20 bg-brand-dark">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Filter Bar -->
      <div class="flex flex-wrap gap-3 mb-12">
        <button class="px-5 py-2 rounded-full bg-brand-primary text-white text-sm font-semibold">Semua</button>
        <button class="px-5 py-2 rounded-full glass text-gray-500 hover:text-white text-sm font-medium transition-all">Oli
          & Pelumas</button>
        <button class="px-5 py-2 rounded-full glass text-gray-500 hover:text-white text-sm font-medium transition-all">Rem
          & Suspensi</button>
        <button
          class="px-5 py-2 rounded-full glass text-gray-500 hover:text-white text-sm font-medium transition-all">Pengapian</button>
        <button
          class="px-5 py-2 rounded-full glass text-gray-500 hover:text-white text-sm font-medium transition-all">Transmisi</button>
      </div>

      <!-- Products -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface">
            <img src="/images/products/oil.png" alt="Engine Oil"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Oli & Pelumas</span>
            <h3 class="font-bold text-base flex-1">Premium Engine Oil SAE 10W-40</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 125.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 24</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Premium+Engine+Oil"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface">
            <img src="/images/products/brakes.png" alt="Brake Pads"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Rem & Suspensi</span>
            <h3 class="font-bold text-base flex-1">Racing Brake Pads Set</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 85.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 18</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Racing+Brake+Pads"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface">
            <img src="/images/products/sparkplug.png" alt="Spark Plug"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Pengapian</span>
            <h3 class="font-bold text-base flex-1">Iridium Spark Plug NGK CR8EIX</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 45.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 40</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Iridium+Spark+Plug"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface">
            <img src="/images/products/chain.png" alt="Chain Set"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Transmisi</span>
            <h3 class="font-bold text-base flex-1">Heavy Duty Chain & Sprocket Set</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 350.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-yellow-400">Stok: 5</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Chain+Sprocket+Set"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface flex items-center justify-center text-gray-700">
            <i class="fa-solid fa-oil-can text-7xl"></i>
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Oli & Pelumas</span>
            <h3 class="font-bold text-base flex-1">Gear Oil Honda MGF 90</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 20.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 60</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Gear+Oil+Honda+MGF+90"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface flex items-center justify-center text-gray-700">
            <i class="fa-solid fa-wind text-7xl"></i>
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Filter</span>
            <h3 class="font-bold text-base flex-1">Filter Udara Racing K&N Style</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 65.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 12</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Filter+Udara+Racing"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface flex items-center justify-center text-gray-700">
            <i class="fa-solid fa-battery-full text-7xl"></i>
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Kelistrikan</span>
            <h3 class="font-bold text-base flex-1">Aki Kering GS GTZ5S MF 12V</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 270.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-green-400">Stok: 8</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Aki+Kering+GS+GTZ5S"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

        <article class="glass-card group flex flex-col">
          <div class="aspect-square overflow-hidden bg-brand-surface flex items-center justify-center text-gray-700">
            <i class="fa-solid fa-arrows-up-down text-7xl"></i>
          </div>
          <div class="p-5 flex flex-col flex-1 gap-3">
            <span class="text-xs text-gray-500 uppercase tracking-wider">Suspensi</span>
            <h3 class="font-bold text-base flex-1">Shock Absorber Belakang KYB</h3>
            <div class="flex items-center justify-between">
              <p class="text-brand-primary font-extrabold text-lg">Rp 450.000</p>
              <span class="text-xs glass px-2 py-0.5 rounded text-red-400">Stok: 2</span>
            </div>
            <a href="https://wa.me/6281234567890?text=Order+Shock+Absorber+KYB"
              class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
              <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
            </a>
          </div>
        </article>

      </div>
    </div>
  </section>

@endsection
