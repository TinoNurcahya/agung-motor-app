@extends('layouts.admin')

@section('title', 'Kelola Produk Toko - Agung Motor')

@section('content')
  <div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black tracking-tight">KELOLA <span class="text-brand-primary">PRODUK</span></h1>
        <p class="text-sm text-muted">Manajemen stok dan harga sparepart/oli toko.</p>
      </div>
      <a href="{{ route('admin.produk.create') }}"
        class="btn-primary py-2.5 px-6 text-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Produk Baru
      </a>
    </div>

    {{-- Stats Mini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="glass-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
          <i class="fa-solid fa-box text-xl"></i>
        </div>
        <div>
          <p class="text-[10px] uppercase font-bold text-muted tracking-widest">Total Produk</p>
          <p class="text-xl font-black">124</p>
        </div>
      </div>
      <div class="glass-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary">
          <i class="fa-solid fa-triangle-exclamation text-xl"></i>
        </div>
        <div>
          <p class="text-[10px] uppercase font-bold text-muted tracking-widest">Stok Menipis</p>
          <p class="text-xl font-black text-brand-primary">12</p>
        </div>
      </div>
      <div class="glass-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500">
          <i class="fa-solid fa-tags text-xl"></i>
        </div>
        <div>
          <p class="text-[10px] uppercase font-bold text-muted tracking-widest">Kategori</p>
          <p class="text-xl font-black">8</p>
        </div>
      </div>
    </div>

    {{-- Filter & Search --}}
    <div class="glass-card p-4 flex flex-col md:flex-row gap-4">
      <div class="flex-1 relative">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-muted text-sm"></i>
        <input type="text" placeholder="Cari nama produk, SKU, atau kategori..."
          class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
      </div>
      <select
        class="bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-2.5 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
        <option value="">Semua Kategori</option>
        <option>Oli Mesin</option>
        <option>Ban Luar/Dalam</option>
        <option>Aki</option>
        <option>Sparepart Mesin</option>
      </select>
    </div>

    {{-- Product Table --}}
    <div class="glass-card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-brand-primary/5 border-b border-brand-primary/10">
              <th class="px-6 py-4 text-[10px] uppercase font-bold tracking-widest text-muted">Produk</th>
              <th class="px-6 py-4 text-[10px] uppercase font-bold tracking-widest text-muted">Kategori</th>
              <th class="px-6 py-4 text-[10px] uppercase font-bold tracking-widest text-muted text-right">Harga Jual</th>
              <th class="px-6 py-4 text-[10px] uppercase font-bold tracking-widest text-muted text-center">Stok</th>
              <th class="px-6 py-4 text-[10px] uppercase font-bold tracking-widest text-muted text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-primary/5">
            @php
              $products = [
                  [
                      'nama' => 'Oli Yamalube Matic 800ml',
                      'cat' => 'Oli Mesin',
                      'harga' => 'Rp 45.000',
                      'stok' => 24,
                      'status' => 'ok',
                  ],
                  [
                      'nama' => 'Ban IRC NR82 80/90-14',
                      'cat' => 'Ban',
                      'harga' => 'Rp 210.000',
                      'stok' => 5,
                      'status' => 'warning',
                  ],
                  [
                      'nama' => 'Aki GS Astra YTZ5S',
                      'cat' => 'Aki',
                      'harga' => 'Rp 245.000',
                      'stok' => 12,
                      'status' => 'ok',
                  ],
                  [
                      'nama' => 'Busi NGK C7HSA',
                      'cat' => 'Sparepart',
                      'harga' => 'Rp 15.000',
                      'stok' => 50,
                      'status' => 'ok',
                  ],
                  [
                      'nama' => 'Kampas Rem Depan Honda Vario',
                      'cat' => 'Sparepart',
                      'harga' => 'Rp 55.000',
                      'stok' => 0,
                      'status' => 'danger',
                  ],
              ];
            @endphp

            @foreach ($products as $p)
              <tr class="hover:bg-brand-primary/5 transition-colors group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-lg bg-brand-surface border border-brand-primary/10 flex items-center justify-center text-muted">
                      <i class="fa-solid fa-image text-xs"></i>
                    </div>
                    <div>
                      <p class="text-sm font-bold">{{ $p['nama'] }}</p>
                      <p class="text-[10px] text-muted uppercase">SKU: AM-PROD-{{ rand(100, 999) }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="text-xs font-medium px-2 py-1 rounded-md bg-brand-primary/5 text-muted">{{ $p['cat'] }}</span>
                </td>
                <td class="px-6 py-4 text-sm font-bold text-right">{{ $p['harga'] }}</td>
                <td class="px-6 py-4">
                  <div class="flex flex-col items-center">
                    <span
                      class="text-sm font-black {{ $p['stok'] == 0 ? 'text-brand-primary' : ($p['stok'] < 10 ? 'text-orange-500' : 'text-green-500') }}">
                      {{ $p['stok'] }}
                    </span>
                    @if ($p['stok'] == 0)
                      <span class="text-[8px] uppercase font-bold text-brand-primary">Habis</span>
                    @elseif($p['stok'] < 10)
                      <span class="text-[8px] uppercase font-bold text-orange-500">Menipis</span>
                    @endif
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.produk.edit', 1) }}"
                      class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center"
                      title="Edit">
                      <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <button
                      class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all"
                      title="Hapus">
                      <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-6 border-t border-brand-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-muted">Menampilkan 1–5 dari 124 produk</p>
        <div class="flex gap-1">
          <button
            class="w-8 h-8 rounded-lg bg-brand-surface border border-brand-primary/10 text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-left"></i></button>
          <button
            class="w-8 h-8 rounded-lg bg-brand-primary text-white text-xs font-bold shadow-lg shadow-brand-primary/20">1</button>
          <button
            class="w-8 h-8 rounded-lg bg-brand-surface border border-brand-primary/10 text-muted text-xs hover:text-brand-primary transition-colors">2</button>
          <button
            class="w-8 h-8 rounded-lg bg-brand-surface border border-brand-primary/10 text-muted text-xs hover:text-brand-primary transition-colors">3</button>
          <button
            class="w-8 h-8 rounded-lg bg-brand-surface border border-brand-primary/10 text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-right"></i></button>
        </div>
      </div>
    </div>
  </div>
@endsection
