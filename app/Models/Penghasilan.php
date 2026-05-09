<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghasilan extends Model
{
    use HasFactory;

    protected $table = 'penghasilan';
    
    protected $fillable = [
        'tanggal',
        'plat_nomor',
        'nama_pemilik',
        'service',
        'sparepart',
        'harga_sparepart',
        'biaya_jasa',
        'total',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_sparepart' => 'decimal:2',
        'biaya_jasa' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Accessor untuk format rupiah
    public function getHargaSparepartFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_sparepart, 0, ',', '.');
    }

    public function getBiayaJasaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->biaya_jasa, 0, ',', '.');
    }

    public function getTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}