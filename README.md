# 🚑 eSIR 2.1 - Sistem Informasi Rujukan Terintegrasi

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Tailwind-4.0-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind 4">
  <img src="https://img.shields.io/badge/Real--time-Reverb-orange?style=for-the-badge&logo=laravel" alt="Reverb">
  <img src="https://img.shields.io/badge/PWA-Ready-green?style=for-the-badge&logo=pwa" alt="PWA Ready">
</p>

**eSIR 2.1** (Electronic Sistem Informasi Rujukan) adalah platform *enterprise-grade* yang dirancang untuk memodernisasi manajemen rujukan pasien antar fasilitas kesehatan (Faskes) di Indonesia. Dengan fokus pada kecepatan, akurasi klinis, dan estetika premium, eSIR 2.1 menghadirkan solusi rujukan yang cerdas dan transparan.

---

## 💎 Visual Excellence & Design Philosophy

Aplikasi ini menggunakan bahasa desain **Medical Light Glassmorphism**, menciptakan antarmuka yang:
- **Profesional & Bersih**: Dominasi warna putih medis dengan aksen biru premium.
- **Modern**: Efek transparansi (glass) dan blur radius pada komponen UI.
- **Responsif**: Dioptimalkan untuk Desktop, Tablet, hingga Smartphone (PWA Ready).
- **Interactive**: Micro-animations yang memberikan feedback instan kepada pengguna.

---

## ✨ Fitur Unggulan (Fase 20)

### 🛰️ Real-time Tracking & Smart Routing
- **Live Ambulance Tracking**: Pantau posisi ambulans secara real-time menggunakan **Laravel Reverb (WebSockets)** & **Leaflet.js**.
- **OSRM Dynamic Routing**: Penghitungan jarak tempuh dan **Estimated Time of Arrival (ETA)** berdasarkan kondisi jalan raya nyata.
- **Smart RS Recommendation**: Algoritma cerdas yang memberikan skor pada RS tujuan berdasarkan:
    - 🛣️ Jarak tempuh jalan raya (OSRM).
    - ⏱️ Estimasi waktu tiba (ETA).
    - 🛏️ Ketersediaan Bed secara real-time (Diskon skor untuk bed yang tersedia).

### 🏥 Clinical Intelligence
- **Clinical Triage Algorithm**: Penentuan prioritas pasien (Merah/Kuning/Hijau) secara otomatis berdasarkan tanda vital.
- **ICD-10 WHO Autocomplete**: Pencarian diagnosa standar internasional yang cepat dan akurat.
- **Patient Journey Timeline**: Rekam jejak historis pasien yang ditampilkan secara kronologis dan intuitif.
- **Professional PDF Audit**: Ekspor surat rujukan dengan standar audit medis, lengkap dengan **Digital Validation QR Code**.

### 🔐 Enterprise Operations
- **QR Code Handover**: Protokol serah terima pasien yang aman; RS tujuan memindai kode QR dari driver untuk mengonfirmasi kedatangan.
- **Hybrid Patient Input**: Fleksibilitas input pasien manual atau menggunakan **Mock Data Generator** untuk simulasi cepat.
- **Offline Sync & GPS Durability**: Penyimpanan koordinat di `localStorage` saat sinyal hilang, otomatis sinkronisasi saat kembali online.
- **Audit Trail**: Pencatatan lengkap setiap perubahan data untuk transparansi dan akuntabilitas.

---

## 🛠️ Tech Stack

### Backend & Real-time
- **Framework**: Laravel 12 (PHP 8.2+)
- **WebSocket Server**: Laravel Reverb (Native WebSockets)
- **Database**: MySQL (dengan dukungan Redis untuk caching)
- **Security**: Spatie Permission (RBAC) & Laravel Sanctum (API Auth)

### Frontend & UI
- **Styling**: Tailwind CSS 4 & Bootstrap 5
- **Build Tool**: Vite
- **Mapping**: Leaflet.js & Leaflet Routing Machine (OSRM)
- **Charts**: Chart.js for Advanced Analytics

---

## 🚀 Instalasi & Setup

### 1. Persiapan Lingkungan
Pastikan Anda memiliki **XAMPP (PHP 8.2+ & MySQL)**, **Composer**, dan **Node.js** terinstal.

### 2. One-Click Setup
Clone repositori ini, lalu jalankan perintah berikut di terminal:
```bash
composer setup
```
*Perintah ini otomatis menginstal dependensi, menyiapkan `.env`, menjalankan migrasi database, dan mem-build aset frontend.*

---

## ⚡ Menjalankan Aplikasi

### 🔧 Prasyarat (Pertama Kali / Fresh Install)
Jalankan perintah berikut jika baru pertama kali menjalankan atau setelah clone:
```bash
# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install

# Generate app key (jika .env belum ada)
cp .env.example .env
php artisan key:generate

# Jalankan migrasi & seeder database
php artisan migrate --seed
```

---

### 🔵 Cara Termudah — One-Click (Windows)
Gunakan skrip otomatis yang sudah tersedia. Buka PowerShell di folder proyek lalu jalankan:
```powershell
.\start_esir.ps1
```
> Skrip ini secara otomatis menjalankan: **MySQL (XAMPP) → Vite (NPM) → Cloudflare Tunnel**.

---

### 🟢 Cara Manual — 4 Terminal Terpisah

Buka **4 terminal** secara bersamaan, jalankan masing-masing perintah berikut:

| Terminal | Perintah | Fungsi |
|----------|----------|--------|
| **1** | `php artisan serve --host=0.0.0.0 --port=8080` | Laravel Web Server |
| **2** | `php artisan reverb:start --host=0.0.0.0 --port=8081` | WebSocket Server (Reverb) |
| **3** | `npm run dev` | Vite (build aset frontend) |
| **4** *(opsional)* | `.\cloudflared.exe tunnel --url http://localhost:8080` | Akses publik via Cloudflare |

---

### 🌐 Akses Aplikasi

Setelah semua layanan berjalan, buka di browser:
- **Lokal (LAN):** `http://192.168.1.9:8080`
- **Publik (HTTPS):** URL yang muncul di terminal Cloudflare (format `https://xxxx.trycloudflare.com`)

### 🟠 Akses HTTPS (Untuk GPS/Geolocation)
Jika memerlukan HTTPS untuk mengaktifkan fitur Geolocation di browser:
1. Jalankan Cloudflare Tunnel untuk Laravel & Reverb:
   ```bash
   .\cloudflared.exe tunnel --url http://localhost:8080
   .\cloudflared.exe tunnel --url http://localhost:8081
   ```
2. Update `APP_URL` dan `REVERB_HOST` di `.env` dengan URL HTTPS yang diberikan Cloudflare.

---

## 📂 Struktur Proyek
- `app/Events/`: Logika broadcasting real-time (Presence Channels).
- `app/Http/Controllers/`: Mesin bisnis utama (Referral, Patient, Ambulance).
- `resources/views/`: UI Templates (Medical Glassmorphism Design).
- `walkthrough.md`: Log pengerjaan lengkap dari Fase 1 hingga 20.

---

## 📝 Catatan Pengembangan
Proyek ini dibangun untuk memenuhi standar interoperabilitas data kesehatan. Penggunaan **Presence Channels** pada Reverb memastikan pelacakan ambulans hanya dapat diakses oleh pihak yang berwenang (Faskes Pengirim & Penerima).

---
*© 2026 eSIR Project Team - Solusi Rujukan Terdepan.*

