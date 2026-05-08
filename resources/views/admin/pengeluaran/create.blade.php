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
      {{-- action="{{ route('admin.pengeluaran.store') }}" method="POST" --}}
      <form class="space-y-6">
        @csrf

        <div>
          <label class="block text-sm font-medium mb-2">Tanggal <span class="text-brand-primary">*</span></label>
          <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Keterangan <span class="text-brand-primary">*</span></label>
          <input type="text" name="keterangan" placeholder="Contoh: Beli Oli Shell Advance 12 Pcs"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Kategori <span class="text-brand-primary">*</span></label>
          <select name="kategori"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none">
            <option value="">-- Pilih Kategori --</option>
            <option value="Stok Barang">Stok Barang</option>
            <option value="Operasional">Operasional</option>
            <option value="Peralatan">Peralatan</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Nominal (Rp) <span class="text-brand-primary">*</span></label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted text-sm font-medium">Rp</span>
            <input type="number" name="nominal" placeholder="0" min="0"
              class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 pl-12 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Catatan</label>
          <textarea name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"
            class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-expense focus:border-brand-expense outline-none resize-none"></textarea>
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
