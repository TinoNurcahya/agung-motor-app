# 🏍️ Agung Motor App — Full-Stack Workshop Management System & AI Analytics

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter&logoColor=white" alt="Flutter">
    <img src="https://img.shields.io/badge/Dart-3.x-0175C2?style=for-the-badge&logo=dart&logoColor=white" alt="Dart">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Database-MySQL%20%2F%20SQLite-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="Database">
    <img src="https://img.shields.io/badge/AI-Forecasting%20Engine-6366F1?style=for-the-badge&logo=google-gemini&logoColor=white" alt="AI Analytics">
</p>

---

## 📌 Pengenalan & Arsitektur Sistem

**Agung Motor Full-Stack System** adalah platform manajemen bengkel terpadu berskala enterprise yang dirancang untuk mendigitalisasi operasional bengkel secara menyeluruh, mulai dari pencatatan kas harian, manajemen inventaris suku cadang (*sparepart*), hingga analisis kecerdasan buatan (*AI Forecasting*).

Sistem ini mengusung arsitektur **Decoupled Client-Server**, yang membagi ekosistem menjadi 3 komponen utama:
1. **Web Admin Dashboard (Laravel Blade + Tailwind CSS):** Antarmuka premium berbasis web untuk administrator utama bengkel.
2. **REST API Core Backend (Laravel Sanctum):** Mesin pemroses data utama yang menyediakan titik akhir (*endpoints*) aman untuk melayani aplikasi seluler.
3. **Mobile Admin Application (Flutter iOS & Android):** Aplikasi seluler berperforma tinggi dengan dukungan mode Terang/Gelap (*Light & Dark Mode*) serta pemantauan nilai tukar mata uang real-time.

```text
+-----------------------------------------------------------------------+
|                       AGUNG MOTOR ECOSYSTEM                           |
+-----------------------------------------------------------------------+

  [ Web Admin Blade ] <---+
   (TailwindCSS & Alpine) |
                          |---> [ Laravel 12 Backend ] <---> [ MySQL / CloudSQL ]
  [ Mobile Flutter App ] <---+   (REST API + Sanctum)
   (Provider State Mgmt)                 ^
                                         |
                               [ AI Forecasting Engine ]
```

---

## 🌟 Keunggulan Utama Proyek

- **Dual-Interface Management:** Kelola operasional bengkel baik dari komputer desktop di kasir maupun dari ponsel pintar saat bepergian.
- **AI Business Insights:** Dilengkapi algoritma cerdas untuk memprediksi pendapatan bulan depan, menghitung persentase retensi pelanggan, dan memberikan daftar rekomendasi pengisian ulang stok suku cadang secara otomatis.
- **Real-Time Currency Exchange Integration:** Aplikasi seluler terhubung langsung dengan API publik eksternal (`open.er-api.com`) untuk menyajikan konversi otomatis Laba Bersih dari Rupiah (IDR) ke Dolar (USD) secara *real-time*.
- **Desain Premium & Glassmorphism:** Antarmuka web dan mobile dirancang menggunakan estetika modern bernuansa *Crimson Red* dan *Deep Black*, lengkap dengan efek *backdrop blur*, animasi transisi yang mulus, dan kartu KPI yang elegan.
- **Laporan PDF & Excel Siap Cetak:** Generator laporan otomatis berbasis DomPDF dan pustaka spreadsheet untuk keperluan audit keuangan.

---

## 🖥️ Fitur Web Dashboard (Laravel Blade)

### 📊 1. Beranda Eksekutif (Dashboard)
- Tampilan kartu metrik kas keuangan (Pemasukan, Pengeluaran, Laba Bersih).
- Grafik interaktif perbandingan pendapatan bulanan menggunakan `Chart.js`.
- Daftar transaksi servis dan penjualan suku cadang terbaru secara langsung.

### 💵 2. Modul Penghasilan (Pemasukan Kas)
- Pencatatan transaksi servis kendaraan, nomor polisi, montir yang bertugas, serta rincian biaya jasa dan suku cadang.
- Kalkulasi otomatis total tagihan secara instan.
- Cetak bukti transaksi dan laporan bulanan ke format PDF / Excel.

### 💳 3. Modul Pengeluaran
- Pengelolaan pengeluaran operasional (pembelian stok barang, gaji, utilitas listrik/air, tagihan, dan pemeliharaan alat).
- Kategorisasi otomatis untuk mempermudah pemantauan arus kas keluar.

### 📦 4. Manajemen Produk & Inventaris
- Pengelolaan master data suku cadang (SKU, Nama Barang, Kategori, Harga Beli/Jual, Stok Saat Ini).
- Sistem peringatan dini (*Early Warning System*) saat persediaan suku cadang menyentuh batas kritis (< 10 unit).

### 📈 5. Statistik Terpadu
- Visualisasi distribusi pengeluaran berdasarkan kategori (*Doughnut Chart*).
- Pemantauan pertumbuhan omset bulan-ke-bulan (*Month-over-Month Growth*).

### 🤖 6. Modul AI Analytics & Rekomendasi Restok
- **Peramalan Keuangan (Revenue Prediction):** AI memproyeksikan estimasi pendapatan bulan depan berdasarkan tren 6 bulan terakhir.
- **Daftar Prioritas Belanja (Restock Recommendations):** Pemeringkatan barang yang wajib segera dibeli ulang berdasarkan tingkat perputaran barang.

---

## 📱 Fitur Mobile App (Flutter)

Aplikasi seluler dibangun menggunakan framework **Flutter** dan bahasa **Dart**, menerapkan pola desain yang sangat bersih dan arsitektur modern:

### 🛠️ Arsitektur & Teknologi Mobile
- **State Management:** Menggunakan pustaka `provider` (`AuthProvider` untuk sesi pengguna, `ThemeProvider` untuk tema warna).
- **Penyimpanan Lokal Persisten:** Memanfaatkan `SharedPreferences` untuk menyimpan token autentikasi, status *Remember Me*, dan preferensi mode tema.
- **API Debouncing & In-Memory Cache:** Mencegah permintaan ganda ke server menggunakan mekanisme *Completer* dan *Timer* di kelas `ApiService`.
- **Parsing Paginasi Otomatis:** Tangguh dalam memproses struktur JSON bersarang dari paginasi Laravel (`data['data']`).

### ✨ Fitur Unggulan Mobile
1. **Autentikasi Premium:** Layar login bergaya *glassmorphism* dengan validasi formulir lengkap, kotak centang *Remember Me*, dan dialog sukses yang interaktif.
2. **Tema Terang & Gelap (Light & Dark Mode):** Tombol *toggle* seketika di AppBar dan laci profil untuk beralih antara skema warna terang yang bersih dan skema warna gelap yang mewah.
3. **Integrasi Kurs Real-Time:** Kartu pemantauan nilai tukar Rupiah terhadap Dolar (IDR/USD) di beranda utama yang memperbarui kurs detik ini juga dari API eksternal.
4. **Modal Notifikasi & Pemberitahuan:** Lembar bawah interaktif untuk menampilkan pemberitahuan sistem dan aktivitas terbaru.
5. **Pengujian Whitebox (Unit Testing):** Dilengkapi *test suite* komprehensif di `test/whitebox_test.dart` untuk menguji integritas logika penyedia sesi dan tema.

---

## 🔌 Dokumentasi REST API Lengkap

Setiap permintaan ke titik akhir yang dilindungi wajib menyertakan header:
```http
Authorization: Bearer <token_sanctum_anda>
Accept: application/json
Content-Type: application/json
```

### 🔑 Modul Autentikasi (`/api`)
| Endpoint | Method | Parameter Body | Struktur Respons Sukses |
|---|---|---|---|
| `/login` | `POST` | `email`, `password` | `{ "success": true, "token": "1|abc...", "user": {...} }` |
| `/register` | `POST` | `name`, `email`, `password` | `{ "success": true, "token": "2|xyz...", "user": {...} }` |
| `/logout` | `POST` | *Tidak ada* | `{ "success": true, "message": "Logged out successfully" }` |
| `/user` | `GET` | *Tidak ada* | `{ "success": true, "data": { "id": 1, "name": "Admin", ... } }` |

### 📊 Modul Beranda & Dasbor (`/api/dashboard`)
| Endpoint | Method | Parameter Query | Deskripsi & Respons |
|---|---|---|---|
| `/overview` | `GET` | `period=30` | Mengembalikan total penghasilan, pengeluaran, dan laba bersih beserta persentase pertumbuhannya. |
| `/chart` | `GET` | `period=30` | Deret tanggal dan nominal kas untuk dirender ke dalam grafik garis. |
| `/recent-transactions`| `GET` | `limit=5` | Mengembalikan 5 transaksi servis atau pengeluaran terakhir. |

### 🛠️ Modul CRUD Utama (`/api`)
| Endpoint | Method | Parameter / Body | Deskripsi |
|---|---|---|---|
| `/penghasilan` | `GET` | `page=1, limit=10` | Daftar riwayat pemasukan bengkel berpaginasi. |
| `/penghasilan` | `POST` | `nama_pemilik`, `total`, `service`, dll | Mencatat transaksi pemasukan/servis baru. |
| `/pengeluaran` | `GET` | `page=1, limit=10` | Daftar pengeluaran operasional berpaginasi. |
| `/pengeluaran` | `POST` | `keterangan`, `nominal`, `kategori` | Mencatat beban biaya pengeluaran baru. |
| `/produk` | `GET` | `search=kampas, page=1` | Katalog inventaris suku cadang bengkel. |
| `/produk/{id}/stock`| `PUT` | `stok` | Memperbarui jumlah persediaan stok barang secara langsung. |

### 🧠 Modul AI & Statistik Lanjutan (`/api`)
| Endpoint | Method | Deskripsi |
|---|---|---|
| `/statistik/summary` | `GET` | Ringkasan metrik laba bersih, pertumbuhan omset, dan 5 layanan teratas. |
| `/statistik/trend` | `GET` | Label tanggal dan deret angka kas untuk grafik tren. |
| `/ai/predictions` | `GET` | Estimasi omset bulan depan dari model regresi peramalan tren. |
| `/ai/stock-health` | `GET` | Rincian jumlah suku cadang sehat, menipis, dan habis. |
| `/ai/recommendations` | `GET` | Daftar prioritas belanja suku cadang yang wajib diisi ulang. |

---

## 🚀 Panduan Instalasi & Konfigurasi Lokal

### Bagian 1: Menjalankan Backend Laravel
1. **Buka folder backend:**
   ```bash
   cd agung-motor-app
   ```
2. **Instal pustaka PHP:**
   ```bash
   composer install
   ```
3. **Instal pustaka JavaScript & kompilasi Tailwind CSS:**
   ```bash
   npm install
   npm run build
   ```
4. **Siapkan file environment:**
   Salin `.env.example` menjadi `.env`, lalu konfigurasikan koneksi database Anda (MySQL / SQLite):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Migrasi struktur tabel dan isi data awal simulasi 6 bulan:**
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan server pengembangan Laravel:**
   ```bash
   php artisan serve
   ```
   *Server backend akan berjalan di `http://localhost:8000`.*

---

### Bagian 2: Menjalankan Mobile App Flutter
1. **Buka folder aplikasi seluler:**
   ```bash
   cd agung_motor_app_mobile
   ```
2. **Unduh semua paket dependensi Flutter:**
   ```bash
   flutter pub get
   ```
3. **Konfigurasi URL Endpoint API (`lib/core/constants/api_constants.dart`):**
   - Jika Anda menjalankan di peramban web (Chrome) atau fisik perangkat yang terhubung jaringan Wi-Fi sama: gunakan IP komputer Anda (misal `http://192.168.1.10:8000`).
   - Jika menggunakan Android Emulator lokal: gunakan `http://10.0.2.2:8000`.
   - Jika menggunakan Chrome Web Debugging di PC yang sama: gunakan `http://localhost:8000`.

4. **Jalankan aplikasi di perangkat atau emulator pilihan Anda:**
   ```bash
   flutter run
   ```

---

## 🧪 Panduan Pengujian (Testing)

Proyek ini dilengkapi pengujian otomatis untuk memverifikasi keakuratan logika aplikasi:

### Menjalankan Unit Test Flutter
Buka terminal di folder `agung_motor_app_mobile` dan jalankan perintah:
```bash
flutter test
```
Ini akan menjalankan seluruh skenario pengujian di dalam `test/whitebox_test.dart` (termasuk pengujian manajemen sesi login/logout, penanganan token, dan pergantian mode tema).

---

## 📜 Lisensi & Atribusi
Sistem ini dirancang, dikembangkan, dan dilisensikan secara eksklusif untuk **Agung Motor Workshop & Sparepart**. Segala hak cipta dilindungi undang-undang.
