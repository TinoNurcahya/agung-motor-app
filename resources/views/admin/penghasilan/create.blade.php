@extends('layouts.admin')

@section('title', 'Tambah Penghasilan')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span>
        <a href="{{ route('admin.penghasilan.index') }}" class="hover:text-brand-primary">Penghasilan</a>
        <span class="mx-1">/</span> Tambah
      </p>
      <h1 class="text-2xl font-bold">Tambah Penghasilan</h1>
      <p class="text-muted text-sm">Lengkapi detail transaksi servis dan penjualan di bawah ini.</p>
    </div>

    {{-- Form Card --}}
    <div class="glass-card p-8">
      <form class="space-y-8" action="{{ route('admin.penghasilan.store') }}" method="POST" id="formPenghasilan">
        @csrf

        @if($errors->any())
          <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-red-500 mb-2">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span class="font-bold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-400">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
          {{-- Data Kendaraan & Pemilik --}}
          <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary">Informasi Kendaraan</h3>

            <div>
              <label class="block text-sm font-medium mb-2">Tanggal Transaksi <span class="text-brand-primary">*</span></label>
              <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none @error('tanggal') border-red-500 @enderror">
              @error('tanggal')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Plat Nomor <span class="text-brand-primary">*</span></label>
              <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" placeholder="Contoh: B 1234 ABC"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none uppercase font-bold @error('plat_nomor') border-red-500 @enderror">
              @error('plat_nomor')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Nama Pemilik <span class="text-brand-primary">*</span></label>
              <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" placeholder="Nama lengkap pemilik"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none @error('nama_pemilik') border-red-500 @enderror">
              @error('nama_pemilik')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Jenis Layanan / Service <span class="text-brand-primary">*</span></label>
              <textarea name="service" rows="2" placeholder="Detail pengerjaan servis"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none resize-none @error('service') border-red-500 @enderror">{{ old('service') }}</textarea>
              @error('service')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Detail Biaya --}}
          <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary">Detail Rincian Biaya</h3>

            <div>
              <label class="block text-sm font-medium mb-2">Sparepart yang Digunakan</label>
              <input type="text" name="sparepart" value="{{ old('sparepart') }}" placeholder="Contoh: Oli Shell, Kanvas Rem"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Harga Sparepart</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted text-xs">Rp</span>
                  <input type="number" name="harga_sparepart" id="harga_sparepart" value="{{ old('harga_sparepart', 0) }}" placeholder="0"
                    class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 pl-10 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none cost-input">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Biaya Jasa</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted text-xs">Rp</span>
                  <input type="number" name="biaya_jasa" id="biaya_jasa" value="{{ old('biaya_jasa', 0) }}" placeholder="0"
                    class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 pl-10 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none cost-input">
                </div>
              </div>
            </div>

            <div class="p-4 rounded-2xl bg-brand-income/5 border border-brand-income/10">
              <label class="block text-[10px] uppercase tracking-widest font-bold text-brand-income mb-1">Total Pembayaran</label>
              <div class="flex items-center gap-2">
                <span class="text-xl font-bold text-brand-income">Rp</span>
                <input type="text" id="total_display" value="0" readonly
                  class="bg-transparent border-none p-0 text-2xl font-black text-brand-income outline-none focus:ring-0 w-full">
                <input type="hidden" name="total" id="total_value" value="0">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Catatan Tambahan</label>
              <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Opsional"
                class="w-full bg-brand-surface border border-brand-primary/10 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-income focus:border-brand-income outline-none">
            </div>
          </div>
        </div>

        <div class="flex gap-4 pt-6 border-t border-brand-primary/10">
          <a href="{{ route('admin.penghasilan.index') }}"
            class="flex-1 glass py-4 rounded-xl font-bold text-sm text-center hover:bg-brand-surface transition-all">
            Batalkan
          </a>
          <button type="submit"
            class="flex-[2] bg-brand-income hover:bg-emerald-600 text-white py-4 rounded-xl font-bold text-sm shadow-lg shadow-brand-income/20 transition-all active:scale-95">
            <i class="fa-solid fa-save mr-2"></i> Simpan Transaksi
          </button>
        </div>
      </form>
    </div>

  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const hargaSparepart = document.getElementById('harga_sparepart');
      const biayaJasa = document.getElementById('biaya_jasa');
      const totalDisplay = document.getElementById('total_display');
      const totalValue = document.getElementById('total_value');

      function calculateTotal() {
        const sparepart = parseInt(hargaSparepart.value) || 0;
        const jasa = parseInt(biayaJasa.value) || 0;
        const total = sparepart + jasa;

        totalDisplay.value = total.toLocaleString('id-ID');
        totalValue.value = total;
      }

      // Initial calculation
      calculateTotal();

      hargaSparepart.addEventListener('input', calculateTotal);
      biayaJasa.addEventListener('input', calculateTotal);
    });
  </script>
@endpush