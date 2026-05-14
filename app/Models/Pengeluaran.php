<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    
    protected $fillable = [
        'slug',
        'tanggal',
        'keterangan',
        'kategori',
        'nominal',
        'catatan',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = \Illuminate\Support\Str::slug(substr($model->keterangan ?: 'pengeluaran', 0, 50));
                $model->slug = $base . '-' . uniqid();
            }
        });
    }

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Accessor untuk format rupiah
    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}