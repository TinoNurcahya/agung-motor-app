@extends('layouts.admin')

@section('title', 'Edit Produk - Agung Motor')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center gap-4">
      <a href="{{ route('admin.produk.index') }}"
        class="w-10 h-10 rounded-xl bg-brand-surface border border-brand-primary/10 flex items-center justify-center text-muted hover:text-brand-primary transition-all">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <div>
        <h1 class="text-2xl font-black tracking-tight">EDIT <span class="text-brand-primary">PRODUK</span></h1>
        <p class="text-sm text-muted">Perbarui data atau stok produk yang sudah terdaftar.</p>
      </div>
    </div>

    <form action="{{ route('admin.produk.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Image Upload Section --}}
      <div class="space-y-4">
        <div class="glass-card p-6 flex flex-col items-center text-center space-y-4">
          <div
            class="w-full aspect-square rounded-2xl bg-brand-surface border-2 border-brand-primary/20 flex flex-col items-center justify-center text-muted relative group overflow-hidden">
            <i class="fa-solid fa-image text-4xl opacity-20"></i>
            <div
              class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
              <i class="fa-solid fa-camera text-white text-2xl"></i>
            </div>
          </div>
          <p class="text-[10px] font-bold uppercase tracking-widest text-brand-primary">Ubah Foto Produk</p>
        </div>

        {{-- Info Stok Card --}}
        <div class="glass-card p-6 bg-orange-500/5 border-orange-500/20">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center text-orange-500">
              <i class="fa-solid fa-layer-group text-sm"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest">Informasi Stok</h3>
          </div>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-xs text-muted">Stok Terkini:</span>
              <span class="text-sm font-black text-orange-500">24 Pcs</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs text-muted">Terjual (Bulan ini):</span>
              <span class="text-sm font-bold">12 Pcs</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Section --}}
      <div class="md:col-span-2 space-y-6">
        <div class="glass-card p-8 space-y-6">
          {{-- Nama Produk --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Nama Produk</label>
            <input type="text" name="nama" value="Oli Yamalube Matic 800ml" required
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Kategori --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Kategori</label>
              <select name="kategori" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
                <option selected>Oli Mesin</option>
                <option>Ban</option>
                <option>Aki</option>
                <option>Busi</option>
                <option>Sparepart</option>
              </select>
            </div>
            {{-- SKU --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">SKU / Kode Produk</label>
              <input type="text" name="sku" value="AM-PROD-882"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Harga Jual --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Harga Jual (Rp)</label>
              <input type="number" name="harga" value="45000" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
            {{-- Stok --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Update Stok</label>
              <input type="number" name="stok" value="24" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Deskripsi Produk</label>
            <textarea name="deskripsi" rows="4"
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none resize-none">Oli resmi untuk motor matic Yamaha. Menjaga suhu mesin tetap stabil dan memberikan pelumasan maksimal.</textarea>
          </div>

          {{-- Action --}}
          <div class="pt-4 border-t border-brand-primary/5 flex justify-between items-center">
            <button type="button" class="text-xs font-bold text-brand-primary hover:underline">
              Hapus Produk Ini
            </button>
            <button type="submit"
              class="btn-primary py-3 px-8 text-sm flex items-center gap-2 shadow-xl shadow-brand-primary/20">
              <i class="fa-solid fa-save"></i> Perbarui Data
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
