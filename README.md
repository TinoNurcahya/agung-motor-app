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

## 📌 Pengenalan & Arsitektur Ekosistem

**Agung Motor Full-Stack System** adalah platform manajemen bengkel terpadu berskala enterprise yang dirancang untuk mendigitalisasi operasional bengkel secara menyeluruh, mulai dari pencatatan kas harian, manajemen inventaris suku cadang (*sparepart*), hingga analisis kecerdasan buatan (*AI Forecasting*).

Sistem ini mengusung arsitektur **Decoupled Client-Server**, yang membagi ekosistem menjadi 3 komponen utama:
1. **Web Admin Dashboard (Laravel Blade + Tailwind CSS):** Antarmuka premium berbasis web untuk administrator utama bengkel.
2. **REST API Core Backend (Laravel Sanctum):** Mesin pemroses data utama yang menyediakan titik akhir (*endpoints*) aman untuk melayani aplikasi seluler.
3. **Mobile Admin Application (Flutter iOS & Android):** Aplikasi seluler berperforma tinggi dengan dukungan mode Terang/Gelap (*Light & Dark Mode*) serta pemantauan nilai tukar mata uang real-time.

---

## 📁 Daftar Modul Dokumentasi (Table of Contents)

Untuk mempermudah eksplorasi teknis, seluruh panduan ekosistem telah dipisahkan ke dalam modul-modul terstruktur di dalam direktori `docs/`:

- [🏛️ Arsitektur Sistem & Ekosistem](docs/architecture.md) — Rincian pembagian tanggung jawab Client-Server dan aliran data AI Forecasting.
- [🖥️ Panduan Modul Web Admin (Blade)](docs/web_admin.md) — Rincian tuntas mengenai 6 modul utama di antarmuka kasir web.
- [📱 Panduan Arsitektur Mobile App (Flutter)](docs/mobile_app.md) — Struktur folder *Feature-First*, manajemen state Provider, serta fitur unggulan seluler.
- [🔌 Referensi REST API Lengkap](docs/api_reference.md) — Tabel spesifikasi titik akhir, metode HTTP, parameter, dan struktur JSON respons.
- [🚀 Panduan Instalasi Lokal & Build APK](docs/installation.md) — Langkah-langkah menjalankan server Laravel, aplikasi Flutter, hingga menghasilkan berkas APK produksi.

---

## 🌟 Keunggulan Utama Proyek

- **Dual-Interface Management:** Kelola operasional bengkel baik dari komputer desktop di kasir maupun dari ponsel pintar saat bepergian.
- **AI Business Insights:** Dilengkapi algoritma cerdas untuk memprediksi pendapatan bulan depan, menghitung persentase retensi pelanggan, dan memberikan daftar rekomendasi pengisian ulang stok suku cadang secara otomatis.
- **Real-Time Currency Exchange Integration:** Aplikasi seluler terhubung langsung dengan API publik eksternal (`open.er-api.com`) untuk menyajikan konversi otomatis Laba Bersih dari Rupiah (IDR) ke Dolar (USD) secara *real-time*.
- **Desain Premium & Glassmorphism:** Antarmuka web dan mobile dirancang menggunakan estetika modern bernuansa *Crimson Red* dan *Deep Black*, lengkap dengan efek *backdrop blur*, animasi transisi yang mulus, dan kartu KPI yang elegan.

---

## 📜 Lisensi & Atribusi
Sistem ini dirancang, dikembangkan, dan dilisensikan secara eksklusif untuk **Agung Motor Workshop & Sparepart**. Segala hak cipta dilindungi undang-undang.
