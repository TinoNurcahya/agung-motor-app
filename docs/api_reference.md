# 🔌 Referensi REST API Lengkap (Laravel Sanctum)

Dokumentasi ini menguraikan spesifikasi teknis dari seluruh titik akhir (*endpoints*) REST API pada backend Agung Motor.

---

## 🔒 Autentikasi & Header Permintaan
Setiap permintaan ke titik akhir yang dilindungi wajib menyertakan header berikut:
```http
Authorization: Bearer <token_sanctum_anda>
Accept: application/json
Content-Type: application/json
```

---

## 🔑 1. Modul Autentikasi (`/api`)
| Endpoint | Method | Parameter Body | Struktur Respons Sukses |
|---|---|---|---|
| `/login` | `POST` | `email`, `password` | `{ "success": true, "token": "1|abc...", "user": {...} }` |
| `/register` | `POST` | `name`, `email`, `password` | `{ "success": true, "token": "2|xyz...", "user": {...} }` |
| `/logout` | `POST` | *Tidak ada* | `{ "success": true, "message": "Logged out successfully" }` |
| `/user` | `GET` | *Tidak ada* | `{ "success": true, "data": { "id": 1, "name": "Admin", ... } }` |

---

## 📊 2. Modul Beranda & Dasbor (`/api/dashboard`)
| Endpoint | Method | Parameter Query | Deskripsi & Respons |
|---|---|---|---|
| `/overview` | `GET` | `period=30` | Mengembalikan total penghasilan, pengeluaran, laba bersih, serta persentase pertumbuhannya. |
| `/chart` | `GET` | `period=30` | Deret tanggal dan nominal kas untuk dirender ke dalam grafik garis. |
| `/recent-transactions`| `GET` | `limit=5` | Mengembalikan 5 transaksi servis atau pengeluaran terakhir. |

---

## 🛠️ 3. Modul CRUD Utama (`/api`)
| Endpoint | Method | Parameter / Body | Deskripsi |
|---|---|---|---|
| `/penghasilan` | `GET` | `page=1, limit=10` | Daftar riwayat pemasukan bengkel berpaginasi. |
| `/penghasilan` | `POST` | `nama_pemilik`, `total`, `service`, dll | Mencatat transaksi pemasukan/servis baru. |
| `/pengeluaran` | `GET` | `page=1, limit=10` | Daftar pengeluaran operasional berpaginasi. |
| `/pengeluaran` | `POST` | `keterangan`, `nominal`, `kategori` | Mencatat beban biaya pengeluaran baru. |
| `/produk` | `GET` | `search=kampas, page=1` | Katalog inventaris suku cadang bengkel. |
| `/produk/{id}/stock`| `PUT` | `stok` | Memperbarui jumlah persediaan stok barang secara langsung. |

---

## 🧠 4. Modul AI & Statistik Lanjutan (`/api`)
| Endpoint | Method | Deskripsi |
|---|---|---|
| `/statistik/summary` | `GET` | Ringkasan metrik laba bersih, pertumbuhan omset, dan 5 layanan teratas. |
| `/statistik/trend` | `GET` | Label tanggal dan deret angka kas untuk grafik tren. |
| `/ai/predictions` | `GET` | Estimasi omset bulan depan dari model regresi peramalan tren. |
| `/ai/stock-health` | `GET` | Rincian jumlah suku cadang sehat, menipis, dan habis. |
| `/ai/recommendations` | `GET` | Daftar prioritas belanja suku cadang yang wajib diisi ulang. |
