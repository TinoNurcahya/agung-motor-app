@extends('layouts.admin')

@section('title', 'Detail Penghasilan')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span>
        <a href="{{ route('admin.penghasilan.index') }}" class="hover:text-brand-primary">Penghasilan</a>
        <span class="mx-1">/</span> Detail
      </p>
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Detail Transaksi Pemasukan</h1>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-income/10 text-brand-income border border-brand-income/20">
          ID: #{{ $penghasilan->id }}
        </span>
      </div>
      <p class="text-muted text-sm">Rincian lengkap dari transaksi servis kendaraan dan penjualan suku cadang.</p>
    </div>

    {{-- Detail Card --}}
    <div class="glass-card p-8 space-y-8">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-brand-primary/10">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-brand-income/10 border border-brand-income/20 flex items-center justify-center text-brand-income shadow-lg">
            <i class="fa-solid fa-money-bill-wave text-2xl"></i>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wider text-muted font-bold">Total Pembayaran</p>
            <p class="text-3xl font-black text-brand-income">Rp {{ number_format($penghasilan->total, 0, ',', '.') }}</p>
          </div>
        </div>
        <div class="text-left md:text-right">
          <p class="text-xs text-muted">Waktu Pencatatan</p>
          <p class="text-sm font-bold text-main">{{ \Carbon\Carbon::parse($penghasilan->tanggal)->translatedFormat('l, d F Y') }}</p>
          <p class="text-xs text-muted">{{ $penghasilan->created_at->format('H:i:s') }}</p>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        {{-- Data Kendaraan --}}
        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary flex items-center gap-2">
            <i class="fa-solid fa-car"></i> Informasi Kendaraan & Klien
          </h3>
          <div class="glass p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Plat Nomor</span>
              <span class="font-black text-sm uppercase text-main">{{ $penghasilan->plat_nomor }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Nama Pemilik</span>
              <span class="font-bold text-sm text-main">{{ $penghasilan->nama_pemilik }}</span>
            </div>
            <div>
              <span class="text-xs text-muted block mb-1">Pengerjaan / Servis</span>
              <p class="text-sm text-main leading-relaxed">{{ $penghasilan->service }}</p>
            </div>
          </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar"></i> Rincian Komponen Biaya
          </h3>
          <div class="glass p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Suku Cadang / Sparepart</span>
              <span class="font-medium text-sm text-main">{{ $penghasilan->sparepart ?: '-' }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Harga Sparepart</span>
              <span class="font-bold text-sm text-main">Rp {{ number_format($penghasilan->harga_sparepart, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Biaya Jasa</span>
              <span class="font-bold text-sm text-main">Rp {{ number_format($penghasilan->biaya_jasa, 0, ',', '.') }}</span>
            </div>
            <div>
              <span class="text-xs text-muted block mb-1">Catatan Tambahan</span>
              <p class="text-xs text-muted italic">{{ $penghasilan->catatan ?: 'Tidak ada catatan.' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex gap-4 pt-6 border-t border-brand-primary/10">
        <a href="{{ route('admin.penghasilan.index') }}"
          class="flex-1 glass py-4 rounded-xl font-bold text-sm text-center hover:bg-brand-surface transition-all">
          <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.penghasilan.edit', $penghasilan->id) }}"
          class="flex-1 bg-brand-primary hover:bg-red-700 text-white py-4 rounded-xl font-bold text-sm text-center shadow-lg shadow-brand-primary/20 transition-all active:scale-95">
          <i class="fa-solid fa-edit mr-2"></i> Edit Transaksi
        </a>
      </div>
    </div>

  </div>
@endsection
