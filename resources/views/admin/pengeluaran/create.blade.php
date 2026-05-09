@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran')

@section('content')
  <div class="max-w-2xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span>
        <a href="{{ route('admin.pengeluaran.index') }}" class="hover:text-brand-primary">Pengeluaran</a>
        <span class="mx-1">/</span> Tambah
      </p>
      <h1 class="text-2xl font-bold">Tambah Pengeluaran</h1>
      <p class="text-muted text-sm">Isi form di bawah untuk mencatat pengeluaran operasional baru.</p>
    </div>

    {{-- Form Card --}}
    <div class="glass-card p-8">
      <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="space-y-6">
        @csrf

        @if($errors->any())
          <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
            <ul class="list-disc list-inside text-sm text-red-400">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div>
          <label class="block text-sm font-medium mb-2">Tanggal <span class="text-brand-primary">*</span></label>
          <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none @error('tanggal') border-red-500 @enderror">
          @error('tanggal')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Keterangan <span class="text-brand-primary">*</span></label>
          <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Beli Oli Shell Advance 12 Pcs"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none @error('keterangan') border-red-500 @enderror">
          @error('keterangan')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Kategori <span class="text-brand-primary">*</span></label>
          <select name="kategori"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none @error('kategori') border-red-500 @enderror">
            <option value="">-- Pilih Kategori --</option>
            <option value="Stok Barang" {{ old('kategori') == 'Stok Barang' ? 'selected' : '' }}>Stok Barang</option>
            <option value="Operasional" {{ old('kategori') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
            <option value="Peralatan" {{ old('kategori') == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
          @error('kategori')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Nominal (Rp) <span class="text-brand-primary">*</span></label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted text-sm font-medium">Rp</span>
            <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="0" min="0"
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 pl-12 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none @error('nominal') border-red-500 @enderror">
          </div>
          @error('nominal')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Catatan</label>
          <textarea name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none resize-none">{{ old('catatan') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
          <a href="{{ route('admin.pengeluaran.index') }}"
            class="flex-1 glass py-3 rounded-xl font-semibold text-sm text-center hover:bg-brand-surface transition-all">
            Batal
          </a>
          <button type="submit"
            class="flex-1 bg-brand-expense hover:bg-red-800 text-white py-3 rounded-xl font-semibold text-sm transition-all active:scale-95">
            <i class="fa-solid fa-check mr-2"></i> Simpan
          </button>
        </div>
      </form>
    </div>

  </div>
@endsection