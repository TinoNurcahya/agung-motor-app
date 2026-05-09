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
        <form method="GET" action="{{ route('admin.pengeluaran.index') }}" id="filterForm">
          <select name="period" onchange="this.form.submit()"
            class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
            <option value="semua" {{ request('period') == 'semua' ? 'selected' : '' }}>Semua</option>
            <option value="bulan_ini" {{ request('period') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="minggu_ini" {{ request('period') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="30_hari" {{ request('period') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
          </select>
        </form>
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
            <p class="text-muted text-xs uppercase tracking-wider">Total {{ $filterText }}</p>
            <h3 class="text-2xl font-bold text-brand-expense mt-0.5">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h3>
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
            <h3 class="text-2xl font-bold mt-0.5">Rp {{ number_format($totalStokBarang ?? 0, 0, ',', '.') }}</h3>
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
            <h3 class="text-2xl font-bold mt-0.5">Rp {{ number_format($totalOperasional ?? 0, 0, ',', '.') }}</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Data Table --}}
    <div class="glass-card overflow-hidden">
      <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-brand-primary/10">
        <h4 class="font-bold">Riwayat Pengeluaran</h4>
        <div class="flex items-center gap-3">
          <form method="GET" action="{{ route('admin.pengeluaran.index') }}" id="searchForm">
            @if(request('period'))
              <input type="hidden" name="period" value="{{ request('period') }}">
            @endif
            <div class="relative">
              <input type="text" name="search" placeholder="Cari pengeluaran..."
                value="{{ request('search') }}"
                class="bg-brand-surface border border-brand-primary/10 rounded-lg px-4 py-2 pl-9 text-sm focus:ring-1 focus:ring-brand-expense w-56">
              <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
            </div>
          </form>
          <a href="{{ route('admin.pengeluaran.export.pdf', request()->all()) }}" 
             class="glass p-2 rounded-lg text-muted hover:text-red-500 hover:bg-red-500/10 transition-colors" 
             title="Export PDF">
            <i class="fa-solid fa-file-pdf text-sm"></i>
          </a>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="text-muted text-xs uppercase tracking-wider">
              <th class="px-6 py-4 font-semibold text-center">No</th>
              <th class="px-6 py-4 font-semibold">Tanggal</th>
              <th class="px-6 py-4 font-semibold">Keterangan</th>
              <th class="px-6 py-4 font-semibold">Kategori</th>
              <th class="px-6 py-4 font-semibold text-right">Nominal</th>
              <th class="px-6 py-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-primary/5">
            @forelse($pengeluaran as $i => $item)
              <tr class="hover:bg-brand-primary/5 transition-colors">
                <td class="px-6 py-4 text-sm text-muted text-center">{{ $pengeluaran->firstItem() + $i }}</td>
                <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->keterangan }}</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                    {{ $item->kategori === 'Stok Barang' ? 'bg-orange-500/10 text-orange-400' : 
                       ($item->kategori === 'Operasional' ? 'bg-yellow-500/10 text-yellow-400' : 
                       'bg-gray-500/10 text-gray-400') }}">
                    {{ $item->kategori }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm font-bold text-brand-expense text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.pengeluaran.edit', $item->id) }}"
                      class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center"
                      title="Edit">
                      <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" 
                          method="POST" 
                          class="inline-block" 
                          onsubmit="return confirm('Yakin hapus pengeluaran {{ $item->keterangan }}?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all"
                        title="Hapus">
                        <i class="fa-solid fa-trash text-xs"></i>
                      </button>
                    </form>
                  </div>
                </td>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-muted">
                  <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                  Tidak ada data pengeluaran
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-6 border-t border-brand-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-muted">
          Menampilkan {{ $pengeluaran->firstItem() ?? 0 }}–{{ $pengeluaran->lastItem() ?? 0 }} 
          dari {{ $pengeluaran->total() ?? 0 }} data
        </p>
        <div class="flex gap-1">
          {{ $pengeluaran->appends(request()->query())->links() }}
        </div>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
  let searchTimeout;
  const searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
      }, 500);
    });
  }
</script>
@endpush