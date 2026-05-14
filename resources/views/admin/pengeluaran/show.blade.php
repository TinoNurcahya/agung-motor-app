@extends('layouts.admin')

@section('title', 'Detail Pengeluaran')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
      <p class="text-xs text-muted mb-1">
        <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
        <span class="mx-1">/</span>
        <a href="{{ route('admin.pengeluaran.index') }}" class="hover:text-brand-primary">Pengeluaran</a>
        <span class="mx-1">/</span> Detail
      </p>
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Detail Transaksi Pengeluaran</h1>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-500/10 text-cyan-500 border border-cyan-500/20">
          ID: #{{ $pengeluaran->id }}
        </span>
      </div>
      <p class="text-muted text-sm">Rincian pengeluaran operasional dan belanja bengkel.</p>
    </div>

    {{-- Detail Card --}}
    <div class="glass-card p-8 space-y-8">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-brand-primary/10">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-500 shadow-lg">
            <i class="fa-solid fa-receipt text-2xl"></i>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wider text-muted font-bold">Nominal Pengeluaran</p>
            <p class="text-3xl font-black text-cyan-500">Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}</p>
          </div>
        </div>
        <div class="text-left md:text-right">
          <p class="text-xs text-muted">Tanggal Pencatatan</p>
          <p class="text-sm font-bold text-main">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->translatedFormat('l, d F Y') }}</p>
          <p class="text-xs text-muted">{{ $pengeluaran->created_at->format('H:i:s') }}</p>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        {{-- Info Pengeluaran --}}
        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary flex items-center gap-2">
            <i class="fa-solid fa-tag"></i> Kategori & Keterangan
          </h3>
          <div class="glass p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-brand-primary/10">
              <span class="text-xs text-muted">Kategori</span>
              <span class="font-bold text-sm text-main px-2 py-0.5 rounded-md bg-brand-primary/10 border border-brand-primary/20">{{ $pengeluaran->kategori }}</span>
            </div>
            <div>
              <span class="text-xs text-muted block mb-1">Keterangan / Tujuan</span>
              <p class="text-sm text-main leading-relaxed font-bold">{{ $pengeluaran->keterangan }}</p>
            </div>
          </div>
        </div>

        {{-- Catatan --}}
        <div class="space-y-4">
          <h3 class="text-sm font-bold uppercase tracking-wider text-brand-primary flex items-center gap-2">
            <i class="fa-solid fa-note-sticky"></i> Catatan Pengeluaran
          </h3>
          <div class="glass p-4 rounded-xl">
            <p class="text-sm text-main leading-relaxed italic">{{ $pengeluaran->catatan ?: 'Tidak ada catatan tambahan untuk pengeluaran ini.' }}</p>
          </div>
        </div>
      </div>

      <div class="flex gap-4 pt-6 border-t border-brand-primary/10">
        <a href="{{ route('admin.pengeluaran.index') }}"
          class="flex-1 glass py-4 rounded-xl font-bold text-sm text-center hover:bg-brand-surface transition-all">
          <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.pengeluaran.edit', $pengeluaran->id) }}"
          class="flex-1 bg-brand-primary hover:bg-red-700 text-white py-4 rounded-xl font-bold text-sm text-center shadow-lg shadow-brand-primary/20 transition-all active:scale-95">
          <i class="fa-solid fa-edit mr-2"></i> Edit Transaksi
        </a>
      </div>
    </div>

  </div>
@endsection
