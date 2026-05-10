@extends('layouts.admin')

@section('title', 'Rekomendasi Restok - Agung Motor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.ai') }}" class="w-10 h-10 rounded-xl bg-brand-surface border border-brand-primary/10 flex items-center justify-center text-muted hover:text-brand-primary transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black tracking-tight">REKOMENDASI <span class="text-cyan-400">RESTOK</span></h1>
            <p class="text-sm text-muted">Daftar produk yang perlu segera di-restok berdasarkan analisis AI</p>
        </div>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="glass-card p-4 text-center">
            <p class="text-muted text-[10px] uppercase tracking-widest">Total Produk</p>
            <p class="text-2xl font-bold">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="glass-card p-4 text-center border-red-500/20">
            <p class="text-muted text-[10px] uppercase tracking-widest">Stok Habis</p>
            <p class="text-2xl font-bold text-red-500">{{ $criticalStock ?? 0 }}</p>
        </div>
        <div class="glass-card p-4 text-center border-orange-500/20">
            <p class="text-muted text-[10px] uppercase tracking-widest">Stok Kritis (&lt;5)</p>
            <p class="text-2xl font-bold text-orange-500">{{ $lowStock ?? 0 }}</p>
        </div>
        <div class="glass-card p-4 text-center border-yellow-500/20">
            <p class="text-muted text-[10px] uppercase tracking-widest">Stok Menipis (5-10)</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $mediumStock ?? 0 }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-muted text-[10px] uppercase tracking-widest">Stok Aman (&gt;20)</p>
            <p class="text-2xl font-bold text-green-500">{{ $healthyStock ?? 0 }}</p>
        </div>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-brand-primary/5 border-b border-brand-primary/10">
                        <th class="px-6 py-4 text-xs uppercase font-bold">Produk</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold">Kategori</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-center">Stok Saat Ini</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-center">Status</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-primary/5">
                    @forelse($products as $product)
                    <tr class="hover:bg-brand-primary/5">
                        <td class="px-6 py-4 font-bold">{{ $product->nama }}</td>
                        <td class="px-6 py-4">{{ $product->kategori }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold {{ $product->stok <= 0 ? 'text-red-500' : ($product->stok <= 5 ? 'text-orange-500' : ($product->stok <= 10 ? 'text-yellow-500' : 'text-green-500')) }}">
                                {{ $product->stok }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->stok <= 0)
                                <span class="px-2 py-1 rounded-full bg-red-500/10 text-red-500 text-xs">Habis</span>
                            @elseif($product->stok <= 5)
                                <span class="px-2 py-1 rounded-full bg-orange-500/10 text-orange-500 text-xs">Segera Restok</span>
                            @elseif($product->stok <= 10)
                                <span class="px-2 py-1 rounded-full bg-yellow-500/10 text-yellow-500 text-xs">Menipis</span>
                            @elseif($product->stok <= 20)
                                <span class="px-2 py-1 rounded-full bg-blue-500/10 text-blue-500 text-xs">Perhatian</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-green-500/10 text-green-500 text-xs">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.produk.edit', $product->id) }}" class="text-cyan-400 hover:underline text-sm">
                                <i class="fa-solid fa-cart-plus"></i> Restok
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-muted">
                            <i class="fa-solid fa-check-circle text-4xl mb-3 block text-green-500"></i>
                            Semua produk dalam kondisi stok aman!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-brand-primary/10">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection