<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    
    protected $fillable = [
        'nama',
        'kategori',
        'sku',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
    ];

    protected $casts = [
        'harga' => 'decimal:0',
        'stok' => 'integer',
    ];

    // Mutator: Format sebelum disimpan ke database
    public function setHargaAttribute($value)
    {
        // Hapus titik dan koma, konversi ke integer
        $cleanValue = preg_replace('/[^0-9]/', '', $value);
        $this->attributes['harga'] = (float) $cleanValue;
    }

    // Accessor: Format saat ditampilkan dari database
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
    
    // Accessor untuk harga asli (numeric)
    public function getHargaRawAttribute()
    {
        return $this->harga;
    }
}