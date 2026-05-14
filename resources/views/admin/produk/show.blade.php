@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span>
        <a href="{{ route('admin.produk.index') }}" class="hover:text-brand-primary">Produk</a>
        <span class="mx-1">/</span> Detail
      </p>
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Detail Informasi Produk</h1>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-primary/10 text-brand-primary border border-brand-primary/20">
          SKU: {{ $produk->sku ?: 'N/A' }}
        </span>
      </div>
      <p class="text-muted text-sm">Rincian spesifikasi, stok, dan harga suku cadang bengkel.</p>
    </div>

    {{-- Detail Card --}}
    <div class="glass-card p-8 space-y-8">
      <div class="grid md:grid-cols-3 gap-8 items-center pb-6 border-b border-brand-primary/10">
        <div class="md:col-span-1 flex justify-center">
          @if($produk->gambar && Storage::disk('public')->exists($produk->gambar))
            <img src="{{ Storage::disk('public')->url($produk->gambar) }}" alt="{{ $produk->nama }}" class="w-full max-w-[200px] h-auto object-cover rounded-2xl shadow-lg border border-brand-primary/20">
          @else
            <div class="w-full max-w-[200px] aspect-square rounded-2xl bg-brand-primary/10 border border-brand-primary/20 flex flex-col items-center justify-center text-brand-primary shadow-lg">
              <i class="fa-solid fa-box-open text-5xl mb-2"></i>
              <span class="text-xs font-bold text-muted">Tanpa Gambar</span>
            </div>
          @endif
        </div>
        <div class="md:col-span-2 space-y-4">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border bg-brand-primary/10 text-brand-primary border-brand-primary/20">{{ $produk->kategori }}</span>
            <h2 class="text-3xl font-black text-main mt-2">{{ $produk->nama }}</h2>
          </div>
          <div class="flex flex-wrap gap-6 pt-2">
            <div>
              <p class="text-xs text-muted uppercase font-bold">Harga Satuan</p>
              <p class="text-2xl font-black text-brand-income">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-xs text-muted uppercase font-bold">Status Stok</p>
              <p class="text-2xl font-black @if($produk->stok <= 0) text-red-500 @elseif($produk->stok < 10) text-amber-500 @else text-brand-income @endif">
                {{ $produk->stok }} Unit
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary flex items-center gap-2">
          <i class="fa-solid fa-align-left"></i> Deskripsi & Informasi Tambahan
        </h3>
        <div class="glass p-6 rounded-2xl">
          <p class="text-sm text-main leading-relaxed whitespace-pre-line">{{ $produk->deskripsi ?: 'Tidak ada deskripsi tersedia untuk produk ini.' }}</p>
        </div>
      </div>

      <div class="flex gap-4 pt-6 border-t border-brand-primary/10">
        <a href="{{ route('admin.produk.index') }}"
          class="flex-1 glass py-4 rounded-xl font-bold text-sm text-center hover:bg-brand-surface transition-all">
          <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.produk.edit', $produk->id) }}"
          class="flex-1 bg-brand-primary hover:bg-red-700 text-white py-4 rounded-xl font-bold text-sm text-center shadow-lg shadow-brand-primary/20 transition-all active:scale-95">
          <i class="fa-solid fa-edit mr-2"></i> Edit Produk
        </a>
      </div>
    </div>

  </div>
@endsection
