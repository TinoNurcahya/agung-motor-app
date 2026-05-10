<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{

    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('min_stok')) {
            $query->where('stok', '<=', $request->min_stok);
        }

        $perPage = $request->get('per_page', 15);
        $produk = $query->orderBy('nama')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $produk,
            'summary' => [
                'total_produk' => Produk::count(),
                'low_stock' => Produk::where('stok', '<', 10)->count(),
                'out_of_stock' => Produk::where('stok', '<=', 0)->count(),
            ]
        ]);
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'sku' => 'nullable|string|max:100|unique:produk,sku',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk', $filename, 'public');
            $data['gambar'] = $path;
        }

        $produk = Produk::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'kategori' => 'sometimes|string|max:100',
            'sku' => 'sometimes|string|max:100|unique:produk,sku,' . $id,
            'harga' => 'sometimes|numeric|min:0',
            'stok' => 'sometimes|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk', $filename, 'public');
            $data['gambar'] = $path;
        }

        $produk->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'data' => $produk
        ]);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'stok' => 'required|integer|min:0',
            'operation' => 'sometimes|in:set,add,subtract'
        ]);

        $operation = $request->get('operation', 'set');
        
        if ($operation === 'add') {
            $produk->stok += $request->stok;
        } elseif ($operation === 'subtract') {
            $produk->stok -= $request->stok;
        } else {
            $produk->stok = $request->stok;
        }

        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok produk berhasil diupdate',
            'data' => $produk
        ]);
    }
}