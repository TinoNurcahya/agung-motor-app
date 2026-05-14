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
        <form method="GET" action="{{ route('admin.penghasilan.index') }}" id="filterForm">
          <select name="period" onchange="this.form.submit()"
            class="bg-brand-surface border border-brand-primary/10 text-xs rounded-lg px-4 py-2 focus:ring-brand-primary focus:border-brand-primary">
            <option value="semua" {{ request('period') == 'semua' ? 'selected' : '' }}>Semua</option>
            <option value="bulan_ini" {{ request('period') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="minggu_ini" {{ request('period') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="30_hari" {{ request('period') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
          </select>
        </form>
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
            <h3 class="text-2xl font-bold text-brand-income mt-0.5">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
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
            <h3 class="text-2xl font-bold mt-0.5">{{ $totalTransaksi ?? 0 }}</h3>
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
            <h3 class="text-2xl font-bold mt-0.5">{{ number_format($marginProfit ?? 0, 1) }}%</h3>
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
          <form method="GET" action="{{ route('admin.penghasilan.index') }}" id="searchForm">
            @if(request('period'))
              <input type="hidden" name="period" value="{{ request('period') }}">
            @endif
            <div class="relative">
              <input type="text" name="search" placeholder="Cari Plat/Owner..."
                value="{{ request('search') }}"
                class="bg-brand-surface border border-brand-primary/10 rounded-lg px-4 py-2 pl-9 text-sm focus:ring-1 focus:ring-brand-income w-64">
              <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
            </div>
          </form>
          <div class="flex items-center gap-3">
          {{-- Tombol Export PDF --}}
          <a href="{{ route('admin.penghasilan.export.pdf', request()->all()) }}" 
            class="glass p-2 rounded-lg text-muted hover:text-red-500 hover:bg-red-500/10 transition-colors" 
            title="Export PDF">
            <i class="fa-solid fa-file-pdf text-sm"></i>
          </a>
          </div>
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
            @forelse($penghasilan as $i => $item)
              <tr class="hover:bg-brand-primary/5 transition-colors group">
                <td class="px-6 py-4 text-sm text-muted text-center">{{ $penghasilan->firstItem() + $i }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-black text-brand-primary">{{ $item->plat_nomor }}</div>
                  <div class="text-[10px] text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-semibold">{{ $item->nama_pemilik }}</div>
                  <div class="text-[10px] text-muted">{{ Str::limit($item->service, 30) }}</div>
                </td>
                <td class="px-6 py-4 text-sm">
                  <span class="text-muted italic">{{ $item->sparepart ?: '-' }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-right text-muted">Rp {{ number_format($item->harga_sparepart, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-right text-muted">Rp {{ number_format($item->biaya_jasa, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-black text-brand-income text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.penghasilan.edit', $item->id) }}"
                      class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center"
                      title="Edit">
                      <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <form action="{{ route('admin.penghasilan.destroy', $item->id) }}" 
                          method="POST" 
                          class="inline-block" 
                          onsubmit="return confirm('Yakin hapus transaksi {{ $item->plat_nomor }}?')">
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
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-6 py-12 text-center text-muted">
                  <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                  Tidak ada data transaksi
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      {{-- Pagination --}}
      <div class="p-6 border-t border-brand-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-muted">
          Menampilkan {{ $penghasilan->firstItem() ?? 0 }}–{{ $penghasilan->lastItem() ?? 0 }} 
          dari {{ $penghasilan->total() ?? 0 }} transaksi
        </p>
        <div class="flex gap-1">
          @if($penghasilan->onFirstPage())
            <button class="w-8 h-8 rounded-lg glass text-muted text-xs opacity-50" disabled>
              <i class="fa-solid fa-chevron-left"></i>
            </button>
          @else
            <a href="{{ $penghasilan->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
               class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors flex items-center justify-center">
              <i class="fa-solid fa-chevron-left"></i>
            </a>
          @endif
          
          @php
            $currentPage = $penghasilan->currentPage();
            $lastPage = $penghasilan->lastPage();
            $start = max(1, $currentPage - 1);
            $end = min($lastPage, $currentPage + 1);
          @endphp
          
          @if($start > 1)
            <a href="{{ $penghasilan->url(1) }}&{{ http_build_query(request()->except('page')) }}" 
               class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors flex items-center justify-center">1</a>
            @if($start > 2)
              <span class="w-8 h-8 rounded-lg glass text-muted text-xs flex items-center justify-center">...</span>
            @endif
          @endif
          
          @for($page = $start; $page <= $end; $page++)
            @if($page == $currentPage)
              <button class="w-8 h-8 rounded-lg bg-brand-primary text-white text-xs font-bold">{{ $page }}</button>
            @else
              <a href="{{ $penghasilan->url($page) }}&{{ http_build_query(request()->except('page')) }}" 
                 class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors flex items-center justify-center">{{ $page }}</a>
            @endif
          @endfor
          
          @if($end < $lastPage)
            @if($end < $lastPage - 1)
              <span class="w-8 h-8 rounded-lg glass text-muted text-xs flex items-center justify-center">...</span>
            @endif
            <a href="{{ $penghasilan->url($lastPage) }}&{{ http_build_query(request()->except('page')) }}" 
               class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors flex items-center justify-center">{{ $lastPage }}</a>
          @endif
          
          @if($penghasilan->hasMorePages())
            <a href="{{ $penghasilan->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
               class="w-8 h-8 rounded-lg glass text-muted text-xs hover:text-brand-primary transition-colors flex items-center justify-center">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          @else
            <button class="w-8 h-8 rounded-lg glass text-muted text-xs opacity-50" disabled>
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          @endif
        </div>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
  // Auto submit search when typing (with debounce)
  let penghasilanSearchTimeout;
  const penghasilanSearchInput = document.querySelector('input[name="search"]');
  if (penghasilanSearchInput) {
    penghasilanSearchInput.addEventListener('input', function() {
      clearTimeout(penghasilanSearchTimeout);
      penghasilanSearchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
      }, 500);
    });
  }
</script>
@endpush