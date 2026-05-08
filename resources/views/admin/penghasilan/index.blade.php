@extends('layouts.admin')

@section('title', 'Penghasilan')

@section('content')
  <div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <p class="text-xs text-muted mb-1">
          <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
          <span class="mx-1">/</span> Penghasilan
        </p>
        <h1 class="text-2xl font-bold">Riwayat Penghasilan</h1>
        <p class="text-muted text-sm">Monitor seluruh transaksi pengerjaan bengkel secara real-time.</p>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <select
          class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
          <option>Bulan Ini</option>
          <option>Minggu Ini</option>
          <option>30 Hari Terakhir</option>
          <option>Semua</option>
        </select>
        <a href="{{ route('admin.penghasilan.create') }}" class="btn-primary py-2 px-4 rounded-lg text-xs">
          <i class="fa-solid fa-plus mr-2"></i>Tambah Penghasilan
        </a>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid sm:grid-cols-3 gap-6">
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-brand-income/10 flex items-center justify-center text-brand-income">
            <i class="fa-solid fa-coins text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-[10px] uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="text-2xl font-bold text-brand-income mt-0.5">Rp 12.350.000</h3>
          </div>
        </div>
      </div>
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
            <i class="fa-solid fa-receipt text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-[10px] uppercase tracking-wider">Total Transaksi</p>
            <h3 class="text-2xl font-bold mt-0.5">48</h3>
          </div>
        </div>
      </div>
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
            <i class="fa-solid fa-arrow-up-right-dots text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-[10px] uppercase tracking-wider">Margin Profit</p>
            <h3 class="text-2xl font-bold mt-0.5">72.4%</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Data Table --}}
    <div class="glass-card overflow-hidden">
      <div
        class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-brand-primary/10">
        <h4 class="font-bold">Daftar Transaksi</h4>
        <div class="flex items-center gap-3">
          <div class="relative">
            <input type="text" placeholder="Cari Plat/Owner..."
              class="bg-brand-surface border border-brand-primary/10 rounded-lg px-4 py-2 pl-9 text-sm focus:ring-1 focus:ring-brand-income w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
          </div>
          <button class="glass p-2 rounded-lg text-muted hover:text-brand-primary transition-colors" title="Export Excel">
            <i class="fa-solid fa-file-excel text-sm"></i>
          </button>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="text-muted text-[10px] uppercase tracking-wider">
              <th class="px-6 py-4 font-bold text-center">No</th>
              <th class="px-6 py-4 font-bold">Plat & Tanggal</th>
              <th class="px-6 py-4 font-bold">Owner & Service</th>
              <th class="px-6 py-4 font-bold">Sparepart</th>
              <th class="px-6 py-4 font-bold text-right">Hrg Sparepart</th>
              <th class="px-6 py-4 font-bold text-right">Jasa</th>
              <th class="px-6 py-4 font-bold text-right">Total</th>
              <th class="px-6 py-4 font-bold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-primary/5">
            @foreach ([['B 1234 ABC', '08 Mei 2026', 'Bapak Agus', 'Servis Rutin + Ganti Oli', 'Oli Shell Advance 1L', 'Rp 65.000', 'Rp 35.000', 'Rp 100.000'], ['D 5678 XYZ', '08 Mei 2026', 'Ibu Maya', 'Ganti Ban Belakang', 'Ban IRC 90/90-14', 'Rp 280.000', 'Rp 20.000', 'Rp 300.000'], ['F 4321 DEF', '07 Mei 2026', 'Mas Andi', 'Overhaul Mesin', 'Paking Top Set, Ring Seher', 'Rp 450.000', 'Rp 300.000', 'Rp 750.000'], ['Z 9999 GH', '07 Mei 2026', 'Pak Budi', 'Servis Kelistrikan', 'Aki GS Astra', 'Rp 250.000', 'Rp 50.000', 'Rp 300.000']] as $i => [$plat, $tgl, $owner, $service, $part, $h_part, $jasa, $total])
              <tr class="hover:bg-brand-primary/5 transition-colors group">
                <td class="px-6 py-4 text-sm text-muted text-center">{{ $i + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-black text-brand-primary">{{ $plat }}</div>
                  <div class="text-[10px] text-muted">{{ $tgl }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-semibold">{{ $owner }}</div>
                  <div class="text-[10px] text-muted">{{ $service }}</div>
                </td>
                <td class="px-6 py-4 text-sm">
                  <span class="text-muted italic">{{ $part ?: '-' }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-right text-muted">{{ $h_part }}</td>
                <td class="px-6 py-4 text-sm text-right text-muted">{{ $jasa }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-black text-brand-income text-right">{{ $total }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.penghasilan.edit', 1) }}"
                      class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center"
                      title="Edit">
                      <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <button
                      class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all">
                      <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{-- Pagination --}}
      <div class="p-6 border-t border-brand-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-muted">Menampilkan 1–4 dari 48 transaksi</p>
        <div class="flex gap-1">
          <button class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-left"></i></button>
          <button class="w-8 h-8 rounded-lg bg-brand-primary text-white text-xs font-bold">1</button>
          <button
            class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors">2</button>
          <button class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-right"></i></button>
        </div>
      </div>
    </div>

  </div>
@endsection
