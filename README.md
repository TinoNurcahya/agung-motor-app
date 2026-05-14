# 📱 Agung Motor App Mobile — Flutter Admin Dashboard & Management

<p align="center">
    <img src="https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter&logoColor=white" alt="Flutter">
    <img src="https://img.shields.io/badge/Dart-3.x-0175C2?style=for-the-badge&logo=dart&logoColor=white" alt="Dart">
    <img src="https://img.shields.io/badge/Platform-Android%20%2F%20iOS%20%2F%20Web-3DDC84?style=for-the-badge&logo=android&logoColor=white" alt="Platforms">
    <img src="https://img.shields.io/badge/State_Management-Provider-FFCA28?style=for-the-badge&logo=flutter&logoColor=white" alt="Provider">
</p>

---

## 📌 Pengenalan Aplikasi Mobile

**Agung Motor Mobile Admin** adalah aplikasi seluler pendamping resmi untuk sistem manajemen bengkel **Agung Motor**. Dibangun menggunakan framework **Flutter**, aplikasi ini dirancang khusus untuk memberikan mobilitas penuh kepada pemilik dan administrator bengkel dalam memantau omset, mengelola stok suku cadang, serta menerima wawasan kecerdasan buatan (*AI Forecasting Insights*) langsung dari genggaman tangan.

---

## ✨ Fitur Unggulan Mobile App

1. **Autentikasi Premium & Glassmorphism:** Layar login modern dengan efek *backdrop blur*, animasi transisi yang mulus, fitur *Remember Me*, dan dialog sukses yang elegan.
2. **Tema Terang & Gelap (Light & Dark Mode):** Tombol *toggle* seketika di AppBar dan laci profil untuk beralih antara skema warna terang yang bersih dan skema warna gelap yang mewah (bernuansa *Crimson Red* dan *Deep Black*).
3. **Integrasi Kurs Real-Time:** Kartu pemantauan nilai tukar Rupiah terhadap Dolar (IDR/USD) di beranda utama yang memperbarui kurs detik ini juga dari API publik eksternal (`open.er-api.com`).
4. **Modal Pemberitahuan Interaktif:** Lembar bawah pop-up (*bottom sheet*) interaktif untuk memonitor aktivitas terbaru di bengkel.
5. **Debouncing & In-Memory Cache:** Menggunakan optimasi jaringan tingkat tinggi untuk mencegah pengiriman permintaan berulang ke server backend.
6. **Parsing Paginasi Tangguh:** Penanganan otomatis untuk menstruktur ulang data JSON yang dibungkus dalam paginasi Laravel.

---

## 🏗️ Struktur Direktori Mobile

Aplikasi ini mengadopsi pola arsitektur **Feature-First** yang sangat teratur dan mudah dikembangkan:

```text
lib/
├── core/
│   ├── constants/
│   │   └── api_constants.dart    # Konfigurasi konstanta URL Endpoint
│   ├── services/
│   │   └── api_service.dart      # Klien HTTP dengan dukungan Debouncing & Auth Header
│   └── theme/
│       ├── app_theme.dart        # Token warna, tipografi, dan gaya Glassmorphism
│       └── theme_provider.dart   # Manajemen state mode Terang/Gelap
├── features/
│   ├── ai/                       # Layar Analisis AI & Rekomendasi Restok Barang
│   ├── auth/                     # Layar Login & Wrapper Autentikasi
│   ├── dashboard/                # Layar Utama Admin & Kartu Nilai Tukar Kurs
│   ├── pengeluaran/              # Layar Riwayat & Pencatatan Pengeluaran
│   ├── penghasilan/              # Layar Riwayat & Pencatatan Servis/Penghasilan
│   ├── produk/                   # Katalog Inventaris & Pemantauan Stok Suku Cadang
│   └── statistik/                # Layar Rangkuman Metrik & Laba Bersih
└── main.dart                     # Titik Masuk Utama & Konfigurasi Provider
```

---

## 🔌 Rangkuman REST API yang Digunakan

Aplikasi mobile ini terhubung langsung dengan backend **Laravel Sanctum**. Pastikan backend sudah menyala sebelum menjalankan aplikasi mobile.

| Modul | Endpoint | Method | Deskripsi |
|---|---|---|---|
| **Auth** | `/api/login` | `POST` | Validasi kredensial dan pengambilan token Sanctum |
| **Auth** | `/api/logout` | `POST` | Pengakhiran sesi dan penghapusan token aktif |
| **Dasbor** | `/api/dashboard/overview` | `GET` | Pengambilan metrik laba bersih dan total kas |
| **Pemasukan**| `/api/penghasilan` | `GET, POST` | Manajemen riwayat servis dan transaksi bengkel |
| **Pengeluaran**|`/api/pengeluaran`| `GET, POST` | Manajemen riwayat belanja dan utilitas bengkel |
| **Produk** | `/api/produk` | `GET, PUT` | Pemantauan persediaan stok dan katalog barang |
| **Statistik**| `/api/statistik/summary` | `GET` | Pengambilan 5 layanan terpopuler bengkel |
| **AI Insights**| `/api/ai/predictions` | `GET` | Proyeksi omset bulan depan dari AI regresi |

---

## 🚀 Panduan Instalasi Lokal & Menjalankan

### 1. Prasyarat Sistem
- **Flutter SDK:** Versi 3.x terbaru.
- **Dart SDK:** Versi 3.x terbaru.
- Perangkat Android fisik, Emulator Android, atau Google Chrome (untuk Web Debugging).

### 2. Langkah-langkah Menjalankan

1. **Buka terminal di dalam folder aplikasi mobile:**
   ```bash
   cd agung_motor_app_mobile
   ```

2. **Unduh semua paket dependensi Flutter:**
   ```bash
   flutter pub get
   ```

3. **Konfigurasi Alamat Backend (`lib/core/constants/api_constants.dart`):**
   Secara bawaan, aplikasi diatur untuk terhubung ke `http://192.168.1.5:8000`.
   - **Jika menggunakan Perangkat Fisik (Wireless Debugging / Kabel USB):** Gunakan IP Address lokal (IPv4) WiFi komputer/PC Anda (misal `http://192.168.1.5:8000`). Pastikan backend Laravel dijalankan dengan flag `--host=0.0.0.0` agar dapat menerima koneksi dari jaringan WiFi lokal.
   - **Jika menggunakan Android Emulator lokal:** Gunakan IP khusus emulator yaitu `http://10.0.2.2:8000`.
   - **Jika menjalankan di peramban web (Chrome Web):** Gunakan `http://localhost:8000`.

   *Catatan: Aplikasi Android telah dilengkapi dengan izin `android:usesCleartextTraffic="true"` di `AndroidManifest.xml` agar koneksi HTTP lokal berjalan lancar tanpa diblokir sistem keamanan Android 9+.*

4. **Jalankan aplikasi:**
   ```bash
   flutter run
   ```

### 3. Fitur In-App Push Notification Popup
Aplikasi mobile dilengkapi dengan sistem notifikasi pintar di dalam aplikasi melalui modul `PushNotificationHelper`. Setiap kali terjadi aktivitas penting (seperti pencatatan penghasilan, pengeluaran, atau penambahan produk baru), aplikasi akan langsung memunculkan popup notifikasi beranimasi premium sebagai umpan balik instan kepada administrator.

---

## 🧪 Panduan Pengujian (Whitebox & Unit Test)

Aplikasi ini dilengkapi dengan skenario pengujian unit (*Unit Test*) dan pengujian logika penyedia state di dalam `test/whitebox_test.dart`.

Untuk menjalankan pengujian otomatis, jalankan perintah berikut di terminal:
```bash
flutter test
```
*Pengujian mencakup verifikasi peralihan status autentikasi, penyimpanan token lokal, serta reaktivitas pergantian mode tema warna.*

---

## 📜 Lisensi & Atribusi
Aplikasi seluler ini dirancang dan dikembangkan khusus sebagai antarmuka pendamping ekosistem **Agung Motor Workshop System**. Segala hak cipta dilindungi undang-undang.
