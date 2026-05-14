<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
        
        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }
        
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }
        
        $produk = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $totalProduk = Produk::count();
        $stokMenipis = Produk::where('stok', '<', 10)->where('stok', '>', 0)->count();
        $kategoriCount = Produk::distinct('kategori')->count('kategori');
        $kategoriList = Produk::distinct('kategori')->pluck('kategori');
        
        return view('admin.produk.index', compact('produk', 'totalProduk', 'stokMenipis', 'kategoriCount', 'kategoriList'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'sku' => 'nullable|string|max:100|unique:produk,sku',
            'harga' => 'required|numeric|min:0', // Tetap validasi numeric
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hapus titik dan koma dari harga jika ada
        if (is_string($request->harga)) {
            // Hapus semua karakter kecuali angka dan titik
            $hargaClean = preg_replace('/[^0-9]/', '', $request->harga);
            $validated['harga'] = (float) $hargaClean;
        }

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $produk = Produk::create($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $produk = Produk::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        return view('admin.produk.show', compact('produk'));
    }

    public function edit($id)
    {
        $produk = Produk::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'sku' => 'nullable|string|max:100|unique:produk,sku,' . $produk->id,
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hapus titik dan koma dari harga jika ada
        if (is_string($request->harga)) {
            $hargaClean = preg_replace('/[^0-9]/', '', $request->harga);
            $validated['harga'] = (float) $hargaClean;
        }

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        
        // Hapus gambar jika ada
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function exportPDF(Request $request)
    {
        $query = Produk::query();
        
        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }
        
        if ($search = $request->get('search')) {
            $query->where('nama', 'like', "%{$search}%");
        }
        
        $produk = $query->orderBy('nama', 'asc')->get();
        
        $totalProduk = $produk->count();
        $totalNilaiStok = $produk->sum(DB::raw('harga * stok'));
        
        $data = [
            'produk' => $produk,
            'totalProduk' => $totalProduk,
            'totalNilaiStok' => $totalNilaiStok,
            'exportDate' => now()->format('d F Y H:i:s'),
        ];
        
        $pdf = Pdf::loadView('admin.produk.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-produk-' . date('Y-m-d-His') . '.pdf');
    }
}