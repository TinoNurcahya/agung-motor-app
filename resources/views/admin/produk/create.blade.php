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

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @csrf
      
      {{-- Image Upload Section --}}
      <div class="space-y-4">
        <div class="glass-card p-6 flex flex-col items-center text-center space-y-4">
          <label for="gambar" class="cursor-pointer w-full">
            <div class="w-full aspect-square rounded-2xl bg-brand-surface border-2 border-dashed border-brand-primary/20 flex flex-col items-center justify-center text-muted group hover:border-brand-primary/50 transition-all cursor-pointer" id="preview-container">
              <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
              <p class="text-[10px] font-bold uppercase tracking-wider">Upload Gambar</p>
              <p class="text-[8px] text-muted/60 mt-1">PNG, JPG up to 2MB</p>
            </div>
            <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
          </label>
          <p class="text-xs text-muted leading-relaxed">
            Gunakan foto produk yang jelas dengan background polos untuk tampilan terbaik di website.
          </p>
          @error('gambar')
            <p class="text-xs text-red-500">{{ $message }}</p>
          @enderror
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
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Oli Yamalube Matic 800ml" required
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none @error('nama') border-red-500 @enderror">
            @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Kategori --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Kategori</label>
              <select name="kategori" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
                <option value="">Pilih Kategori</option>
                <option value="Oli Mesin" {{ old('kategori') == 'Oli Mesin' ? 'selected' : '' }}>Oli Mesin</option>
                <option value="Ban" {{ old('kategori') == 'Ban' ? 'selected' : '' }}>Ban</option>
                <option value="Aki" {{ old('kategori') == 'Aki' ? 'selected' : '' }}>Aki</option>
                <option value="Busi" {{ old('kategori') == 'Busi' ? 'selected' : '' }}>Busi</option>
                <option value="Sparepart" {{ old('kategori') == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
              </select>
            </div>
            {{-- SKU --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">SKU / Kode Produk</label>
              <input type="text" name="sku" value="{{ old('sku') }}" placeholder="AM-PROD-001"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Harga Jual --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Harga Jual (Rp)</label>
              <input type="text" name="harga" id="harga" value="{{ old('harga') }}" placeholder="0" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none"
                oninput="formatRupiah(this)">
            </div>
            {{-- Stok Awal --}}
            <div class="space-y-2">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Stok Awal</label>
              <input type="number" name="stok" value="{{ old('stok', 0) }}" placeholder="0" required
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted">Deskripsi Produk</label>
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan spesifikasi atau keunggulan produk..."
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary outline-none resize-none">{{ old('deskripsi') }}</textarea>
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

@push('scripts')
<script>
  function previewImage(event) {
    const container = document.getElementById('preview-container');
    const file = event.target.files[0];
    
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-2xl">`;
        container.classList.remove('border-dashed');
      }
      reader.readAsDataURL(file);
    } else {
      container.innerHTML = `
        <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
        <p class="text-[10px] font-bold uppercase tracking-wider">Upload Gambar</p>
        <p class="text-[8px] text-muted/60 mt-1">PNG, JPG up to 2MB</p>
      `;
      container.classList.add('border-dashed');
    }

    function formatRupiah(input) {
      // Hapus semua karakter non-digit
      let value = input.value.replace(/[^,\d]/g, '');
      
      // Pisahkan integer dan desimal
      let parts = value.split(',');
      let integer = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      
      if (parts.length > 1) {
        input.value = integer + ',' + parts[1].substring(0, 2);
      } else {
        input.value = integer;
      }
    }
    
    function previewImage(event) {
      const container = document.getElementById('preview-container');
      const file = event.target.files[0];
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-2xl">`;
          container.classList.remove('border-dashed');
        }
        reader.readAsDataURL(file);
      }
    }
  }
</script>
@endpush