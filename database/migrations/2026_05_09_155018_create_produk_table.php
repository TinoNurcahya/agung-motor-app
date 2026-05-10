<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('kategori', 100);
            $table->string('sku', 100)->unique()->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('kategori');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};