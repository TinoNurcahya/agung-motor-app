# 🏛️ Arsitektur Sistem & Ekosistem Agung Motor

Sistem Manajemen Bengkel **Agung Motor** dirancang dengan pendekatan arsitektur **Decoupled Client-Server** berskala *enterprise*. Pendekatan ini memisahkan lapisan presentasi antarmuka (Web & Mobile) dari logika inti pemrosesan data (REST API Backend), memungkinkan skalabilitas tinggi dan pemeliharaan jangka panjang yang mudah.

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

## 1. Lapisan Inti (Core REST API Backend)
- **Framework:** Laravel 12.x
- **Autentikasi:** Laravel Sanctum (Token-based Auth)
- **Database:** MySQL / MariaDB (Mendukung transaksi dengan relasi Eloquent yang tangguh)
- **Tanggung Jawab:** Bertindak sebagai *Single Source of Truth* yang memvalidasi data, mencatat transaksi kas, mengelola stok suku cadang, dan menjalankan algoritma peramalan bisnis.

## 2. Lapisan Antarmuka Web Admin (Blade Monolith)
- **Teknologi:** Laravel Blade Template, Tailwind CSS 3.x, Alpine.js, Chart.js.
- **Tanggung Jawab:** Menyediakan dasbor eksekutif bagi kasir dan pengelola bengkel di tempat. Menyajikan laporan keuangan grafis yang siap diekspor ke PDF dan Excel.

## 3. Lapisan Antarmuka Mobile Admin (Flutter Client)
- **Teknologi:** Flutter 3.x, Dart 3.x, Provider State Management.
- **Tanggung Jawab:** Memberikan akses kendali penuh dari jarak jauh (*remote management*) melalui perangkat Android dan iOS. Menampilkan indikator kurs mata uang Rupiah/Dolar secara *real-time*.

## 4. Mesin Kecerdasan Buatan (AI Forecasting Engine)
Sistem dilengkapi dengan modul analisis regresi tren yang memproses riwayat transaksi 6 bulan ke belakang guna memproyeksikan estimasi omset bulan depan serta menentukan prioritas pengisian ulang suku cadang secara cerdas.
