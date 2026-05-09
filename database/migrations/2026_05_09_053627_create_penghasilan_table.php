<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penghasilan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('plat_nomor', 20);
            $table->string('nama_pemilik', 100);
            $table->text('service');
            $table->string('sparepart', 255)->nullable();
            $table->decimal('harga_sparepart', 15, 2)->default(0);
            $table->decimal('biaya_jasa', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('plat_nomor');
            $table->index('tanggal');
            $table->index('nama_pemilik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghasilan');
    }
};