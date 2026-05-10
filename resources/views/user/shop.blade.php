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
        <a href="{{ route('user.shop') }}" 
           class="px-5 py-2 rounded-full {{ !request('kategori') ? 'bg-brand-primary text-white' : 'glass text-gray-500 hover:text-white' }} text-sm font-semibold transition-all">
          Semua
        </a>
        @foreach(\App\Models\Produk::distinct('kategori')->pluck('kategori') as $kat)
          <a href="{{ route('user.shop', ['kategori' => $kat]) }}" 
             class="px-5 py-2 rounded-full {{ request('kategori') == $kat ? 'bg-brand-primary text-white' : 'glass text-gray-500 hover:text-white' }} text-sm font-medium transition-all">
            {{ $kat }}
          </a>
        @endforeach
      </div>

      <!-- Products -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($produk as $item)
          <article class="glass-card group flex flex-col">
            <div class="aspect-square overflow-hidden bg-brand-surface">
              @if($item->gambar && Storage::disk('public')->exists($item->gambar))
                <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama }}"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
              @else
                <div class="w-full h-full flex items-center justify-center text-gray-700">
                  <i class="fa-solid fa-oil-can text-7xl"></i>
                </div>
              @endif
            </div>
            <div class="p-5 flex flex-col flex-1 gap-3">
              <span class="text-xs text-gray-500 uppercase tracking-wider">{{ $item->kategori }}</span>
              <h3 class="font-bold text-base flex-1">{{ $item->nama }}</h3>
              <div class="flex items-center justify-between">
                <p class="text-brand-primary font-extrabold text-lg">{{ $item->harga_formatted }}</p>
                <span class="text-xs glass px-2 py-0.5 rounded {{ $item->stok <= 0 ? 'text-red-400' : ($item->stok < 10 ? 'text-yellow-400' : 'text-green-400') }}">
                  Stok: {{ $item->stok }}
                </span>
              </div>
              <a href="https://wa.me/6281234567890?text=Order+{{ urlencode($item->nama) }}"
                class="w-full btn-whatsapp py-2 text-sm justify-center rounded-xl">
                <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp
              </a>
            </div>
          </article>
        @empty
          <div class="col-span-full text-center py-12">
            <i class="fa-solid fa-inbox text-5xl text-muted mb-4 block"></i>
            <p class="text-muted">Belum ada produk tersedia</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

@endsection