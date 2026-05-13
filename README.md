# 🏍️ Agung Motor App — AI-Powered Workshop Management System

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Alpine.js-3.14-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
    <img src="https://img.shields.io/badge/Vite-7.0-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
    <img src="https://img.shields.io/badge/Database-SQLite%20%7C%20MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="Database">
</p>

---

## 📖 Tentang Aplikasi

**Agung Motor App** adalah sistem manajemen bengkel modern berbasis web dan API yang dirancang untuk mempermudah operasional harian bengkel, pencatatan keuangan, manajemen inventaris suku cadang, dan pengenalan analitik cerdas berbasis kecerdasan buatan (**AI Analytics & Forecasting**).

Aplikasi ini dibangun menggunakan arsitektur **Laravel 12** yang tangguh, antarmuka pengguna interaktif dengan **Tailwind CSS** dan **Alpine.js**, serta menyediakan kapabilitas REST API berkinerja tinggi yang diamankan menggunakan **Laravel Sanctum**.

---

## ✨ Fitur Utama (Key Features)

### 📊 1. AI Analytics & Business Forecasting
- **Prediksi Omset (Revenue Prediction):** AI menganalisis tren pertumbuhan dari riwayat transaksi 6 bulan terakhir untuk memprediksi pendapatan di bulan berikutnya.
- **Kesehatan Stok (Stock Health Monitoring):** Deteksi dini dan otomatis untuk barang dengan stok menipis (< 10) atau habis, memberikan skala prioritas restok otomatis (Kritis, Tinggi, Sedang, Rendah).
- **Analisis Retensi Pelanggan (Customer Retention):** Melacak tingkat loyalitas pelanggan aktif dari bulan ke bulan.
- **Dynamic AI Strategy:** Menghasilkan saran strategi bisnis otomatis berdasarkan pola hari/minggu tersibuk dan suku cadang paling sering dicari.

### 💰 2. Manajemen Keuangan (Pemasukan & Pengeluaran)
- Pencatatan transaksi **Penghasilan** (servis dan penjualan suku cadang) dengan detail nama pelanggan, montir, dan rincian biaya.
- Pencatatan **Pengeluaran** operasional bengkel secara terstruktur.
- Ekspor laporan keuangan secara instan ke format **PDF** dan **Excel**.
- Fitur *Bulk Store* untuk mengimpor transaksi dalam jumlah besar.

### 📦 3. Manajemen Inventaris Suku Cadang (Produk)
- Katalog produk dengan kategorisasi lengkap (Oli, Ban, Kampas Rem, Aki, dll.).
- Pemantauan sisa stok secara real-time.
- Pembaruan stok cepat dan ekspor katalog ke PDF.

### 📱 4. Portal Pengguna & Landing Page
- **Landing Page Eksklusif:** Menampilkan informasi layanan bengkel, galeri produk/shop, halaman tentang kami, dan kontak.
- **User Dashboard:** Memberikan pengalaman interaktif bagi pelanggan aktif bengkel untuk melihat status layanan dan suku cadang.

### 🔌 5. REST API yang Aman (Sanctum Integration)
- Mendukung integrasi aplikasi seluler (Flutter/Android/iOS) atau *frontend* terpisah dengan *endpoint* API yang komprehensif.

---

## 🚀 Panduan Instalasi & Konfigurasi

Ikuti langkah-langkah di bawah ini untuk menjalankan **Agung Motor App** di lingkungan lokal Anda.

### Persyaratan Sistem (Prerequisites)
- **PHP** versi 8.2 atau yang lebih baru
- **Composer** v2+
- **Node.js** v18+ dan **NPM**
- Database: **SQLite** (default) atau **MySQL** / **PostgreSQL**

### Langkah Instalasi

1. **Kloning Repositori:**
   ```bash
   git clone https://github.com/TinoNurcahya/agung-motor-app.git
   cd agung-motor-app
   ```

2. **Instal Dependensi PHP (Composer):**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   Salin file konfigurasi `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   > **Catatan Database:** Secara default, Laravel 12 mendukung SQLite. Jika Anda menggunakan SQLite, jalankan perintah di langkah 5 untuk membuat file database secara otomatis. Jika menggunakan MySQL, sesuaikan konfigurasi `DB_*` di dalam file `.env`.

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi Database:**
   ```bash
   php artisan migrate --seed
   ```

6. **Instal & Bangun Dependensi Frontend (NPM):**
   ```bash
   npm install
   npm run build
   ```

7. **Menjalankan Server Pengembangan Lokal:**
   Anda dapat menggunakan skrip otomatis yang menjalankan server Laravel dan Vite secara bersamaan:
   ```bash
   composer run dev
   ```
   Atau secara manual di dua terminal terpisah:
   ```bash
   php artisan serve
   npm run dev
   ```

Aplikasi sekarang dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 📂 Struktur Direktori Penting

```text
agung-motor-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Logic untuk Panel Admin (Keuangan, Inventaris, AI)
│   │   ├── Api/            # Endpoint REST API
│   │   └── Auth/           # Otentikasi & Registrasi
│   └── Models/             # Model Eloquent (Penghasilan, Pengeluaran, Produk)
├── database/
│   ├── migrations/         # Struktur Tabel Database
│   └── seeders/            # Data Awal (Dummy Data)
├── resources/
│   ├── views/              # Template Blade (Admin, User, Landing Page)
│   └── css/js/             # Aset Tailwind CSS dan Alpine.js
└── routes/
    ├── web.php             # Rute Aplikasi Web & Dashboard Admin
    └── api.php             # Rute Endpoint REST API
```

---

## 📡 Dokumentasi REST API

Semua rute API diawali dengan `/api`. Endpoint yang bersifat *protected* wajib menyertakan *header*:
`Authorization: Bearer <token_anda>`

### Otentikasi
- `POST /api/login` — Login pengguna dan mendapatkan token.
- `POST /api/register` — Mendaftarkan pengguna baru.
- `POST /api/logout` — Mengakhiri sesi (Protected).

### Ringkasan Endpoint Utama (Protected)
| Metode | Endpoint | Deskripsi |
|---|---|---|
| `GET` | `/api/dashboard/overview` | Mendapatkan ringkasan statistik harian/bulanan |
| `GET` | `/api/penghasilan` | Daftar transaksi pemasukan bengkel |
| `POST` | `/api/penghasilan` | Menambah transaksi pemasukan baru |
| `GET` | `/api/pengeluaran` | Daftar transaksi pengeluaran operasional |
| `GET` | `/api/produk` | Katalog produk dan ketersediaan stok |
| `PUT` | `/api/produk/{id}/stock`| Memperbarui stok suku cadang |
| `GET` | `/api/ai/predictions` | Mendapatkan analisis prediksi omset bulan depan |
| `GET` | `/api/ai/stock-health`| Analisis status kesehatan dan rekomendasi restok |
| `GET` | `/api/search` | Pencarian layanan, suku cadang, dan transaksi |

---

## 🤝 Kontribusi (Contributing)

Kami menyambut segala bentuk kontribusi untuk mengembangkan **Agung Motor App**.
1. Fork repositori ini.
2. Buat *branch* fitur baru (`git checkout -b fitur/NamaFitur`).
3. Lakukan *commit* perubahan Anda (`git commit -m 'Menambahkan fitur baru'`).
4. *Push* ke *branch* tersebut (`git push origin fitur/NamaFitur`).
5. Ajukan *Pull Request*.

---

## 🛡️ Lisensi (License)

Proyek ini bersifat *open-source* dan didistribusikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
