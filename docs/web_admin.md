# 🖥️ Panduan Modul Web Admin (Laravel Blade)

Antarmuka Web Admin Agung Motor dibangun menggunakan kombinasi **Laravel Blade Template** dan pustaka utilitas **Tailwind CSS**, menghadirkan desain visual bergaya *Modern Indigo Dark Mode* dengan elemen *glassmorphism*.

---

## 📊 1. Beranda Eksekutif (Dashboard)
- **Kartu Metrik Keuangan:** Memantau Pemasukan Hari Ini, Pengeluaran Operasional, serta Laba Bersih secara instan.
- **Visualisasi Dinamis:** Menggunakan `Chart.js` untuk merender grafik batang dan garis yang membandingkan performa kas bengkel.
- **Aktivitas Terkini:** Tabel transaksi servis kendaraan dan penjualan suku cadang yang masuk secara *real-time*.

---

## 💵 2. Modul Penghasilan (Pemasukan Kas)
- **Pencatatan Servis:** Formulir lengkap mencakup nama pelanggan, nomor polisi kendaraan, mekanik yang bertugas, biaya jasa montir, dan suku cadang yang digunakan.
- **Perhitungan Otomatis:** Sistem langsung menjumlahkan total tagihan secara akurat.
- **Ekspor Laporan:** Dukungan pencetakan struk dan laporan pembukuan bulanan ke dalam format dokumen PDF (*DomPDF*) maupun spreadsheet Excel.

---

## 💳 3. Modul Pengeluaran
- **Pengelolaan Beban Usaha:** Mencatat seluruh arus kas keluar, mulai dari kulakan stok suku cadang, pembayaran tagihan listrik/air/internet, hingga gaji karyawan bengkel.
- **Kategorisasi Cerdas:** Pengelompokan pengeluaran secara teratur (`Stok Barang`, `Operasional`, `Peralatan`, dan `Lainnya`) untuk memudahkan proses audit akuntansi.

---

## 📦 4. Manajemen Produk & Inventaris Suku Cadang
- **Master Data Sparepart:** Mengelola kode SKU, nama barang, harga beli, harga jual, foto produk, serta jumlah stok yang tersedia.
- **Sistem Peringatan Stok (Early Warning System):** Munculnya notifikasi peringatan otomatis saat persediaan suatu suku cadang menyentuh batas menipis (< 10 unit) atau habis.

---

## 📈 5. Statistik Terpadu
- **Analisis Persentase Arus Kas:** Menampilkan grafik distribusi pengeluaran (*Doughnut Chart*) berdasarkan porsi kategori.
- **Pertumbuhan Laba:** Perbandingan margin keuntungan bersih (*Net Profit Margin*) dari bulan ke bulan.

---

## 🤖 6. Modul AI Analytics & Rekomendasi Restok
- **Peramalan Finansial (Revenue Forecasting):** Algoritma memproyeksikan estimasi omset bulan berikutnya berdasarkan pola pertumbuhan historis.
- **Prioritas Belanja Suku Cadang:** Mengurutkan barang yang paling mendesak untuk dibeli ulang berdasarkan tingkat perputaran dan sisa stok.
