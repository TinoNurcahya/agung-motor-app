<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        // Search penghasilan
        $penghasilan = Penghasilan::where('plat_nomor', 'like', "%{$query}%")
            ->orWhere('nama_pemilik', 'like', "%{$query}%")
            ->orWhere('service', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'penghasilan',
                    'plat_nomor' => $item->plat_nomor,
                    'nama_pemilik' => $item->nama_pemilik,
                    'service' => $item->service,
                    'total' => $item->total,
                    'total_formatted' => 'Rp ' . number_format($item->total, 0, ',', '.'),
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                ];
            });
        
        // Search pengeluaran
        $pengeluaran = Pengeluaran::where('keterangan', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'pengeluaran',
                    'keterangan' => $item->keterangan,
                    'kategori' => $item->kategori,
                    'nominal' => $item->nominal,
                    'nominal_formatted' => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                ];
            });
        
        // Search produk
        $produk = Produk::where('nama', 'like', "%{$query}%")
            ->orWhere('kategori', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'produk',
                    'nama' => $item->nama,
                    'kategori' => $item->kategori,
                    'sku' => $item->sku,
                    'harga' => $item->harga,
                    'harga_formatted' => 'Rp ' . number_format($item->harga, 0, ',', '.'),
                    'stok' => $item->stok,
                ];
            });
        
        $results = $penghasilan->concat($pengeluaran)->concat($produk);
        
        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => $results->count(),
            'data' => $results->values(),
            'grouped' => [
                'penghasilan' => $penghasilan->values(),
                'pengeluaran' => $pengeluaran->values(),
                'produk' => $produk->values(),
            ]
        ]);
    }
}