# 🚀 Panduan Instalasi & Konfigurasi Sistem Lokal

Panduan ini memandu Anda langkah demi langkah dalam menyiapkan lingkungan pengembangan lokal untuk menjalankan ekosistem **Agung Motor**.

---

## Bagian 1: Menyiapkan Backend Laravel
1. **Buka folder proyek backend:**
   ```bash
   cd agung-motor-app
   ```
2. **Instal pustaka PHP melalui Composer:**
   ```bash
   composer install
   ```
3. **Instal pustaka JavaScript & kompilasi Tailwind CSS:**
   ```bash
   npm install
   npm run build
   ```
4. **Siapkan konfigurasi environment:**
   Salin berkas contoh konfigurasi lingkungan menjadi berkas aktif:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Jalankan migrasi tabel dan pengisian simulasi data 6 bulan:**
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan server pengembangan lokal:**
   ```bash
   php artisan serve
   ```
   *Server backend akan menyala dan dapat diakses di `http://localhost:8000`.*

---

## Bagian 2: Menyiapkan Mobile App Flutter
1. **Buka folder proyek aplikasi mobile:**
   ```bash
   cd agung_motor_app_mobile
   ```
2. **Unduh semua paket dependensi Flutter:**
   ```bash
   flutter pub get
   ```
3. **Konfigurasi Alamat API Backend (`lib/core/constants/api_constants.dart`):**
   - Jika Anda menjalankan di peramban web (Chrome) atau fisik perangkat yang terhubung jaringan Wi-Fi sama: gunakan IP komputer Anda (misal `http://192.168.1.10:8000`).
   - Jika menggunakan Android Emulator lokal: gunakan `http://10.0.2.2:8000`.

4. **Jalankan aplikasi di perangkat fisik atau emulator:**
   ```bash
   flutter run
   ```

---

## Bagian 3: Membangun APK Versi Rilis (Release Build)
Untuk menghasilkan berkas APK produksi yang siap dipasang atau didistribusikan di GitHub Releases:

1. Buka terminal di folder `agung_motor_app_mobile`.
2. Jalankan perintah kompilasi rilis:
   ```bash
   flutter build apk --release
   ```
3. Berkas APK hasil kompilasi akan berada di lokasi:
   ```text
   build/app/outputs/flutter-apk/app-release.apk
   ```
