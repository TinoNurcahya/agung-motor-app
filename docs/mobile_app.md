# 📱 Panduan Arsitektur & Fitur Mobile App (Flutter)

Aplikasi mobile **Agung Motor Admin** dirancang menggunakan framework **Flutter 3.x** dan bahasa **Dart 3.x**, menerapkan pola desain yang bersih dengan performa tinggi.

---

## 🏗️ Struktur Direktori (Feature-First Architecture)

Struktur proyek diatur berdasarkan modul fitur (*Feature-First*), yang mempermudah navigasi kode dan pengembangan tim:

```text
lib/
├── core/
│   ├── constants/
│   │   └── api_constants.dart    # Konfigurasi titik akhir API backend
│   ├── services/
│   │   └── api_service.dart      # Klien jaringan dengan dukungan Debouncing
│   └── theme/
│       ├── app_theme.dart        # Definisi palet warna Crimson Red & Deep Black
│       └── theme_provider.dart   # Manajemen state peralihan tema
├── features/
│   ├── ai/                       # Layar AI Analytics & Prediksi Omset
│   ├── auth/                     # Layar Login & Wrapper Sesi
│   ├── dashboard/                # Layar Utama Admin & Kartu Kurs Mata Uang
│   ├── pengeluaran/              # Layar Pencatatan Pengeluaran Operasional
│   ├── penghasilan/              # Layar Pencatatan Transaksi Servis
│   ├── produk/                   # Layar Katalog & Pemantauan Stok Barang
│   └── statistik/                # Layar Grafik Laba Bersih & Top Services
└── main.dart                     # Konfigurasi MultiProvider & MaterialApp
```

---

## ✨ Fitur & Keunggulan Mobile App

### 1. Autentikasi Premium bergaya Glassmorphism
Antarmuka login dengan efek *backdrop filter blur*, animasi transisi masuk yang mulus, serta kotak centang *Remember Me* yang menyimpan kredensial di `SharedPreferences`.

### 2. Tema Terang & Gelap (Light & Dark Mode)
Mendukung peralihan tema warna seketika (*instant toggle*) melalui tombol di AppBar maupun laci menu samping. Menggunakan kombinasi warna elegan *Crimson Red* dan *Deep Black*.

### 3. Pemantauan Kurs Mata Uang Real-Time
Beranda dasbor utama dilengkapi kartu khusus yang menarik nilai tukar Rupiah terhadap Dolar (IDR/USD) secara langsung dari API eksternal `open.er-api.com`. Kartu ini secara otomatis menghitung ekuivalen nilai Laba Bersih bengkel ke dalam Dolar Amerika Serikat.

### 4. Lembar Notifikasi Interaktif (Bottom Sheet)
Menyajikan pemberitahuan peringatan stok suku cadang dan ringkasan aktivitas bengkel dalam jendela pop-up tengah layar atau lembar bawah yang elegan.

### 5. Optimasi Jaringan (Debouncing)
Penerapan kelas `Completer` dan `Timer` di dalam `ApiService` untuk mencegah pengiriman permintaan berulang yang berlebihan ke server backend.

---

## 🧪 Pengujian Unit & Whitebox Testing
Aplikasi ini menyertakan *test suite* otomatis di `test/whitebox_test.dart` untuk memverifikasi integritas penyedia autentikasi (`AuthProvider`) dan penyedia tema warna (`ThemeProvider`).
