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

    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @csrf
      @method('PUT')
      
      {{-- Image Upload Section --}}
      <div class="space-y-4">
        <div class="glass-card p-6 flex flex-col items-center text-center space-y-4">
          <label for="gambar" class="cursor-pointer w-full">
            <div class="w-full aspect-square rounded-2xl bg-brand-surface border-2 border-brand-primary/20 flex flex-col items-center justify-center text-muted relative group overflow-hidden" id="preview-container">
              @if($produk->gambar && Storage::disk('public')->exists($produk->gambar))
                <img src="{{ Storage::url($produk->gambar) }}" class="w-full h-full object-cover">
              @else
                <i class="fa-solid fa-image text-4xl opacity-20"></i>
              @endif
              <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fa-solid fa-camera text-white text-2xl"></i>
              </div>
            </div>
            <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
          </label>
          <p class="text-[10px] font-bold uppercase tracking-widest text-brand-primary">Klik untuk Ubah Foto Produk</p>
          @error('gambar')
            <p class="text-xs text-red-500">{{ $message }}</p>
          @enderror
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
              <span class="text-sm font-black text-orange-500">{{ $produk->stok }} Pcs</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Section --}}
      <div class="md:col-span-2 space-y-6">
        <div class="glass-card p-8 space-y-6">
          @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
              <ul class="list-disc list-inside text-sm text-red-400">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Nama Produk --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Nama Produk</label>
            <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Kategori --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Kategori</label>
              <select name="kategori" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
                <option value="Oli Mesin" {{ old('kategori', $produk->kategori) == 'Oli Mesin' ? 'selected' : '' }}>Oli Mesin</option>
                <option value="Ban" {{ old('kategori', $produk->kategori) == 'Ban' ? 'selected' : '' }}>Ban</option>
                <option value="Aki" {{ old('kategori', $produk->kategori) == 'Aki' ? 'selected' : '' }}>Aki</option>
                <option value="Busi" {{ old('kategori', $produk->kategori) == 'Busi' ? 'selected' : '' }}>Busi</option>
                <option value="Sparepart" {{ old('kategori', $produk->kategori) == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
              </select>
            </div>
            {{-- SKU --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">SKU / Kode Produk</label>
              <input type="text" name="sku" value="{{ old('sku', $produk->sku) }}" placeholder="AM-PROD-001"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Harga Jual --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Harga Jual (Rp)</label>
              <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
            {{-- Stok --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Update Stok</label>
              <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Deskripsi Produk</label>
            <textarea name="deskripsi" rows="4"
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
          </div>

          {{-- Action --}}
          <div class="pt-4 border-t border-brand-primary/5 flex justify-end">
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

@push('scripts')
<script>
  function previewImage(event) {
    const container = document.getElementById('preview-container');
    const file = event.target.files[0];
    
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-2xl">`;
      }
      reader.readAsDataURL(file);
    }
  }
</script>
@endpush