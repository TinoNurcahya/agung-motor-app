@extends('layouts.admin')

@section('title', 'Pengeluaran')

@section('content')
  <div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <p class="text-xs text-muted mb-1">
          <a href="{{ route('admin.index') }}" class="hover:text-brand-primary">Dashboard</a>
          <span class="mx-1">/</span> Pengeluaran
        </p>
        <h1 class="text-2xl font-bold">Pengeluaran</h1>
        <p class="text-muted text-sm">Kelola semua data pengeluaran operasional bengkel.</p>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <select
          class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
          <option>Bulan Ini</option>
          <option>Minggu Ini</option>
          <option>30 Hari Terakhir</option>
          <option>Semua</option>
        </select>
        <a href="{{ route('admin.pengeluaran.create') }}" class="btn-primary py-2 px-4 rounded-lg text-xs">
          <i class="fa-solid fa-plus mr-2"></i>Tambah Pengeluaran
        </a>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid sm:grid-cols-3 gap-6">
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-brand-expense/10 flex items-center justify-center text-brand-expense">
            <i class="fa-solid fa-money-bill-wave text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-xs uppercase tracking-wider">Total Bulan Ini</p>
            <h3 class="text-2xl font-bold text-brand-expense mt-0.5">Rp 4.850.000</h3>
          </div>
        </div>
      </div>
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400">
            <i class="fa-solid fa-boxes-stacked text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-xs uppercase tracking-wider">Belanja Stok</p>
            <h3 class="text-2xl font-bold mt-0.5">Rp 3.200.000</h3>
          </div>
        </div>
      </div>
      <div class="glass-card p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-400">
            <i class="fa-solid fa-toolbox text-xl"></i>
          </div>
          <div>
            <p class="text-muted text-xs uppercase tracking-wider">Operasional</p>
            <h3 class="text-2xl font-bold mt-0.5">Rp 1.650.000</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Data Table --}}
    <div class="glass-card overflow-hidden">
      <div
        class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-brand-primary/10">
        <h4 class="font-bold">Riwayat Pengeluaran</h4>
        <div class="flex items-center gap-3">
          <div class="relative">
            <input type="text" placeholder="Cari pengeluaran..."
              class="bg-brand-surface border border-brand-primary/10 rounded-lg px-4 py-2 pl-9 text-sm focus:ring-1 focus:ring-brand-expense w-56">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
          </div>
          <button class="glass p-2 rounded-lg text-muted hover:text-brand-primary transition-colors" title="Export">
            <i class="fa-solid fa-download text-sm"></i>
          </button>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="text-muted text-xs uppercase tracking-wider">
              <th class="px-6 py-4 font-semibold">No</th>
              <th class="px-6 py-4 font-semibold">Tanggal</th>
              <th class="px-6 py-4 font-semibold">Keterangan</th>
              <th class="px-6 py-4 font-semibold">Kategori</th>
              <th class="px-6 py-4 font-semibold text-right">Nominal</th>
              <th class="px-6 py-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-primary/5">
            @foreach ([['08 Mei 2026', 'Beli Oli Shell Advance (12 Pcs)', 'Stok Barang', 'Rp 650.000'], ['08 Mei 2026', 'Bayar Listrik Bengkel', 'Operasional', 'Rp 450.000'], ['07 Mei 2026', 'Beli Ban IRC (6 Pcs)', 'Stok Barang', 'Rp 1.200.000'], ['07 Mei 2026', 'Beli Kampas Rem (20 Set)', 'Stok Barang', 'Rp 400.000'], ['06 Mei 2026', 'Gaji Mekanik Harian', 'Operasional', 'Rp 600.000'], ['06 Mei 2026', 'Beli Busi NGK (1 Box)', 'Stok Barang', 'Rp 350.000'], ['05 Mei 2026', 'Air Minum & Snack Bengkel', 'Operasional', 'Rp 75.000'], ['05 Mei 2026', 'Beli Aki GS (4 Pcs)', 'Stok Barang', 'Rp 1.080.000']] as $i => [$tgl, $ket, $kategori, $nominal])
              <tr class="hover:bg-brand-primary/5 transition-colors">
                <td class="px-6 py-4 text-sm text-muted">{{ $i + 1 }}</td>
                <td class="px-6 py-4 text-sm">{{ $tgl }}</td>
                <td class="px-6 py-4 text-sm">{{ $ket }}</td>
                <td class="px-6 py-4">
                  <span
                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $kategori === 'Stok Barang' ? 'bg-orange-500/10 text-orange-400' : 'bg-yellow-500/10 text-yellow-400' }}">
                    {{ $kategori }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm font-bold text-brand-expense text-right">{{ $nominal }}</td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.pengeluaran.edit', 1) }}"
                      class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center"
                      title="Edit">
                      <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <button
                      class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all"
                      title="Hapus">
                      <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-6 border-t border-brand-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-muted">Menampilkan 1–8 dari 32 data</p>
        <div class="flex gap-1">
          <button class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-left"></i></button>
          <button class="w-8 h-8 rounded-lg bg-brand-primary text-white text-xs font-bold">1</button>
          <button
            class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors">2</button>
          <button
            class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors">3</button>
          <button class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors"><i
              class="fa-solid fa-chevron-right"></i></button>
        </div>
      </div>
    </div>

  </div>
@endsection
