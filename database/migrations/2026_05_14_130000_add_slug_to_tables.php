<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penghasilan', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Populate existing records with slugs
        $penghasilans = DB::table('penghasilan')->get();
        foreach ($penghasilans as $p) {
            $slug = Str::slug(substr($p->service ?: 'pemasukan', 0, 50)) . '-' . $p->id;
            DB::table('penghasilan')->where('id', $p->id)->update(['slug' => $slug]);
        }

        $pengeluarans = DB::table('pengeluaran')->get();
        foreach ($pengeluarans as $p) {
            $slug = Str::slug(substr($p->keterangan ?: 'pengeluaran', 0, 50)) . '-' . $p->id;
            DB::table('pengeluaran')->where('id', $p->id)->update(['slug' => $slug]);
        }

        $produks = DB::table('produk')->get();
        foreach ($produks as $p) {
            $slug = Str::slug(substr($p->nama ?: 'produk', 0, 50)) . '-' . $p->id;
            DB::table('produk')->where('id', $p->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('penghasilan', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
