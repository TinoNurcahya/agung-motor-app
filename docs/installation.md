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
6. **Jalankan server pengembangan lokal (Untuk Jaringan / HP Fisik):**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   *Server backend akan melayani koneksi dari perangkat dalam satu jaringan WiFi di port 8000.*

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
   Secara bawaan menggunakan `http://192.168.1.5:8000`.
   - **Jika menggunakan HP Fisik / Wireless Debugging:** Gunakan IP Address komputer/PC Anda di jaringan WiFi (misal `http://192.168.1.5:8000`).
   - **Jika menggunakan Android Emulator lokal:** Gunakan `http://10.0.2.2:8000`.
   - **Jika menggunakan Browser Web:** Gunakan `http://localhost:8000`.

   *Catatan: Konfigurasi Android di `AndroidManifest.xml` telah diatur dengan `android:usesCleartextTraffic="true"` agar mengizinkan koneksi HTTP lokal.*

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
