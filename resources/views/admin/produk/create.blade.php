@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Agung Motor')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center gap-4">
      <a href="{{ route('admin.produk.index') }}"
        class="w-10 h-10 rounded-xl bg-brand-surface border border-brand-primary/10 flex items-center justify-center text-muted hover:text-brand-primary transition-all">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <div>
        <h1 class="text-2xl font-black tracking-tight">TAMBAH <span class="text-brand-primary">PRODUK</span></h1>
        <p class="text-sm text-muted">Input data sparepart atau oli baru ke sistem toko.</p>
      </div>
    </div>

    <form action="{{ route('admin.produk.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Image Upload Section --}}
      <div class="space-y-4">
        <div class="glass-card p-6 flex flex-col items-center text-center space-y-4">
          <div
            class="w-full aspect-square rounded-2xl bg-brand-surface border-2 border-dashed border-brand-primary/20 flex flex-col items-center justify-center text-muted group hover:border-brand-primary/50 transition-all cursor-pointer">
            <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
            <p class="text-[10px] font-bold uppercase tracking-wider">Upload Gambar</p>
            <p class="text-[8px] text-muted/60 mt-1">PNG, JPG up to 2MB</p>
          </div>
          <p class="text-xs text-muted leading-relaxed">
            Gunakan foto produk yang jelas dengan background polos untuk tampilan terbaik di website.
          </p>
        </div>
      </div>

      {{-- Form Section --}}
      <div class="md:col-span-2 space-y-6">
        <div class="glass-card p-8 space-y-6">
          {{-- Nama Produk --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Nama Produk</label>
            <input type="text" name="nama" placeholder="Contoh: Oli Yamalube Matic 800ml" required
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Kategori --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Kategori</label>
              <select name="kategori" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
                <option value="">Pilih Kategori</option>
                <option>Oli Mesin</option>
                <option>Ban</option>
                <option>Aki</option>
                <option>Busi</option>
                <option>Sparepart</option>
              </select>
            </div>
            {{-- SKU --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">SKU / Kode Produk</label>
              <input type="text" name="sku" placeholder="AM-PROD-001"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Harga Jual --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Harga Jual (Rp)</label>
              <input type="number" name="harga" placeholder="0" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
            {{-- Stok Awal --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Stok Awal</label>
              <input type="number" name="stok" placeholder="0" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Deskripsi Produk</label>
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan spesifikasi atau keunggulan produk..."
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none resize-none"></textarea>
          </div>

          {{-- Action --}}
          <div class="pt-4 border-t border-brand-primary/5 flex justify-end">
            <button type="submit"
              class="btn-primary py-3 px-8 text-sm flex items-center gap-2 shadow-xl shadow-brand-primary/20">
              <i class="fa-solid fa-save"></i> Simpan Produk
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
