<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@agungmotor.com'],
            [
                'name' => 'Admin Agung Motor',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Data Dummy Produk / Sparepart dengan variasi stok (Kritis, Rendah, Aman)
        $produkData = [
            ['nama' => 'Oli Yamalube Super Sport 1L', 'kategori' => 'Oli', 'sku' => 'OLI-YML-001', 'harga' => 85000, 'stok' => 45, 'deskripsi' => 'Oli sintetis penuh untuk motor sport Yamaha'],
            ['nama' => 'Oli Motul 5100 10W-40 1L', 'kategori' => 'Oli', 'sku' => 'OLI-MTL-002', 'harga' => 125000, 'stok' => 30, 'deskripsi' => 'Oli ester berkualitas tinggi untuk performa maksimal'],
            ['nama' => 'Kampas Rem Depan Honda Beat', 'kategori' => 'Sparepart', 'sku' => 'KMP-HND-001', 'harga' => 45000, 'stok' => 50, 'deskripsi' => 'Kampas rem asli Honda Genuine Parts'],
            ['nama' => 'Kampas Rem Belakang Yamaha NMAX', 'kategori' => 'Sparepart', 'sku' => 'KMP-YMH-002', 'harga' => 60000, 'stok' => 40, 'deskripsi' => 'Kampas rem belakang Yamaha Genuine Parts'],
            ['nama' => 'Busi NGK Iridium CPR9EAIX-9', 'kategori' => 'Sparepart', 'sku' => 'BSI-NGK-001', 'harga' => 95000, 'stok' => 25, 'deskripsi' => 'Busi iridium untuk pembakaran lebih sempurna'],
            ['nama' => 'Ban Tubeless Maxxis 90/80-14', 'kategori' => 'Ban', 'sku' => 'BAN-MXS-001', 'harga' => 235000, 'stok' => 20, 'deskripsi' => 'Ban motor matic tubeless dengan daya cengkeram tinggi'],
            ['nama' => 'Ban Michelin City Grip Pro 100/80-14', 'kategori' => 'Ban', 'sku' => 'BAN-MCL-002', 'harga' => 310000, 'stok' => 15, 'deskripsi' => 'Ban premium anti bocor dan tahan lama'],
            ['nama' => 'Aki GS Astra MF GTZ6V', 'kategori' => 'Aki', 'sku' => 'AKI-GSA-001', 'harga' => 280000, 'stok' => 18, 'deskripsi' => 'Aki kering bebas perawatan untuk motor Vario/NMAX'],
            ['nama' => 'V-Belt Gates Honda Vario 125', 'kategori' => 'Sparepart', 'sku' => 'VBL-GTS-001', 'harga' => 135000, 'stok' => 22, 'deskripsi' => 'Vanbelt transmisi matic tahan panas'],
            ['nama' => 'Filter Udara Ferrox Yamaha Aerox', 'kategori' => 'Aksesoris', 'sku' => 'FLT-FRX-001', 'harga' => 450000, 'stok' => 12, 'deskripsi' => 'Filter udara stainless steel seumur hidup'],
            // Produk stok rendah & kritis untuk memicu notifikasi AI
            ['nama' => 'Lampu LED Ayoto H6 Matic', 'kategori' => 'Aksesoris', 'sku' => 'LED-AYT-001', 'harga' => 65000, 'stok' => 6, 'deskripsi' => 'Lampu depan putih terang hemat daya'],
            ['nama' => 'Minyak Rem Jumbo DOT 4 300ml', 'kategori' => 'Oli', 'sku' => 'MNY-JMB-001', 'harga' => 25000, 'stok' => 3, 'deskripsi' => 'Minyak rem standar tinggi untuk pengereman pakem'],
            ['nama' => 'Bantalan Stir / Komstir Honda Vario', 'kategori' => 'Sparepart', 'sku' => 'KMS-HND-001', 'harga' => 110000, 'stok' => 2, 'deskripsi' => 'Komstir original untuk handling stabil'],
            ['nama' => 'Kabel Gas Yamaha Mio M3', 'kategori' => 'Sparepart', 'sku' => 'KBG-YMH-001', 'harga' => 35000, 'stok' => 0, 'deskripsi' => 'Kabel gas lentur anti seret'],
        ];

        foreach ($produkData as $p) {
            Produk::updateOrCreate(['sku' => $p['sku']], $p);
        }

        // 3. Data Dummy Pemasukan (Penghasilan) tersebar di 6 bulan terakhir dengan tren kenaikan omset
        // Pemilik kendaraan bervariasi agar retensi pelanggan terhitung dengan baik
        $pelanggan = [
            'Budi Santoso', 'Ahmad Fauzi', 'Siti Rahma', 'Rizky Pratama', 'Dewi Lestari', 
            'Hendra Gunawan', 'Agung Purnomo', 'Ratna Sari', 'Eko Prasetyo', 'Triyana',
            'Fajar Nugraha', 'Maya Sofiana', 'Wahyu Hidayat', 'Dina Mariana', 'Kurniawan'
        ];

        $servicesList = [
            ['service' => 'Servis Rutin + Ganti Oli', 'sparepart' => 'Oli Yamalube Super Sport 1L', 'harga_sparepart' => 85000, 'biaya_jasa' => 40000],
            ['service' => 'Ganti Kampas Rem Depan', 'sparepart' => 'Kampas Rem Depan Honda Beat', 'harga_sparepart' => 45000, 'biaya_jasa' => 25000],
            ['service' => 'Tune Up + Ganti Busi', 'sparepart' => 'Busi NGK Iridium CPR9EAIX-9', 'harga_sparepart' => 95000, 'biaya_jasa' => 35000],
            ['service' => 'Ganti Ban Tubeless', 'sparepart' => 'Ban Tubeless Maxxis 90/80-14', 'harga_sparepart' => 235000, 'biaya_jasa' => 40000],
            ['service' => 'Ganti Aki MF', 'sparepart' => 'Aki GS Astra MF GTZ6V', 'harga_sparepart' => 280000, 'biaya_jasa' => 20000],
            ['service' => 'Servis CVT + Ganti V-Belt', 'sparepart' => 'V-Belt Gates Honda Vario 125', 'harga_sparepart' => 135000, 'biaya_jasa' => 45000],
            ['service' => 'Ganti Oli Motul Premium', 'sparepart' => 'Oli Motul 5100 10W-40 1L', 'harga_sparepart' => 125000, 'biaya_jasa' => 20000],
            ['service' => 'Ganti Lampu LED Depan', 'sparepart' => 'Lampu LED Ayoto H6', 'harga_sparepart' => 65000, 'biaya_jasa' => 15000],
        ];

        // Hapus data lama agar bersih
        Penghasilan::truncate();
        Pengeluaran::truncate();

        // Seed 6 bulan terakhir (bulan -5 hingga bulan 0)
        for ($m = 5; $m >= 0; $m--) {
            // Jumlah transaksi per bulan meningkat seiring waktu (mewakili pertumbuhan)
            $numTransactions = 12 + ((5 - $m) * 4); // Bulan 5: 12 tx, Bulan 0: 32 tx
            
            for ($i = 0; $i < $numTransactions; $i++) {
                // Tentukan tanggal acak dalam bulan tersebut
                $targetDate = Carbon::now()->subMonths($m)->startOfMonth()->addDays(rand(0, 27));
                
                // Pilih pelanggan & service
                $cust = $pelanggan[array_rand($pelanggan)];
                $srv = $servicesList[array_rand($servicesList)];
                $plat = 'B ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)) . chr(rand(65, 90));

                Penghasilan::create([
                    'plat_nomor' => $plat,
                    'nama_pemilik' => $cust,
                    'service' => $srv['service'],
                    'sparepart' => $srv['sparepart'],
                    'harga_sparepart' => $srv['harga_sparepart'],
                    'biaya_jasa' => $srv['biaya_jasa'],
                    'total' => $srv['harga_sparepart'] + $srv['biaya_jasa'],
                    'catatan' => 'Servis berkala berjalan lancar',
                    'tanggal' => $targetDate->format('Y-m-d'),
                ]);
            }
            
            // Seed Pengeluaran per bulan (stok barang, operasional, peralatan)
            $pengeluaranBulanan = [
                ['keterangan' => 'Pembelian Stok Oli & Sparepart Rutin', 'kategori' => 'Stok Barang', 'nominal' => 2500000 + rand(-200000, 500000)],
                ['keterangan' => 'Tagihan Listrik, Air & Internet', 'kategori' => 'Operasional', 'nominal' => 850000 + rand(-50000, 100000)],
                ['keterangan' => 'Gaji Mekanik & Staf Bengkel', 'kategori' => 'Operasional', 'nominal' => 6000000],
                ['keterangan' => 'Perawatan & Kalibrasi Alat Bengkel', 'kategori' => 'Peralatan', 'nominal' => 450000 + rand(-100000, 200000)],
                ['keterangan' => 'Iuran Keamanan & Retribusi Daerah', 'kategori' => 'Lainnya', 'nominal' => 200000],
            ];

            foreach ($pengeluaranBulanan as $peng) {
                $pDate = Carbon::now()->subMonths($m)->startOfMonth()->addDays(rand(1, 25));
                Pengeluaran::create([
                    'keterangan' => $peng['keterangan'],
                    'kategori' => $peng['kategori'],
                    'nominal' => $peng['nominal'],
                    'catatan' => 'Pengeluaran bulanan bengkel',
                    'tanggal' => $pDate->format('Y-m-d'),
                ]);
            }
        }
    }
}
