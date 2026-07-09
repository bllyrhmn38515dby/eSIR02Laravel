# 🚑 eSIR 2.1 — Sistem Informasi Rujukan Terintegrasi

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/WebSocket-Reverb-orange?style=for-the-badge&logo=laravel" alt="Reverb">
  <img src="https://img.shields.io/badge/Maps-Leaflet.js-green?style=for-the-badge&logo=leaflet" alt="Leaflet">
  <img src="https://img.shields.io/badge/PWA-Ready-purple?style=for-the-badge&logo=pwa" alt="PWA Ready">
  <img src="https://img.shields.io/badge/GPS-Live%20Tracking-brightgreen?style=for-the-badge" alt="GPS Tracking">
</p>

**eSIR 2.1** (Electronic Sistem Informasi Rujukan) adalah platform *enterprise-grade* yang dirancang untuk memodernisasi manajemen rujukan pasien antar fasilitas kesehatan (Faskes) di Indonesia. Dengan fokus pada kecepatan, akurasi klinis, dan estetika premium, eSIR 2.1 menghadirkan solusi rujukan yang cerdas, transparan, dan **real-time**.

---

## 💎 Visual Excellence & Design Philosophy

Aplikasi ini menggunakan bahasa desain **Medical Light Glassmorphism**, menciptakan antarmuka yang:
- **Profesional & Bersih**: Dominasi warna putih medis dengan aksen biru premium.
- **Modern**: Efek transparansi (glass) dan blur radius pada komponen UI.
- **Responsif**: Dioptimalkan untuk Desktop, Tablet, hingga Smartphone (PWA Ready).
- **Interactive**: Micro-animations yang memberikan feedback instan kepada pengguna.

---

## ✨ Fitur Unggulan

### 🛰️ Real-time Ambulance Tracking (Fase Terkini)

Sistem pelacakan ambulans eSIR 2.1 menggunakan **arsitektur dual-layer** untuk menjamin posisi selalu terupdate:

#### Lapisan 1 — WebSocket Whisper (Ultra Real-time)
- **Client-to-Client Whisper**: Driver mengirimkan posisi GPS & arah kompas langsung ke browser Faskes/Admin via WebSocket (Laravel Reverb) **setiap 200ms** — tanpa melewati server PHP, tanpa delay database.
- **Device Compass Integration**: Ikon ambulans di peta berputar mengikuti arah kompas HP supir secara instan (menggunakan `deviceorientation` / `deviceorientationabsolute`).
- **Presence Channel Authorization**: Keamanan berbasis role — hanya Driver yang ditugaskan, Faskes asal/tujuan, dan Admin Pusat yang dapat masuk ke ruang siaran.

#### Lapisan 2 — Database Polling (Fallback Anti-Gagal)
- **Auto-Polling setiap 2 detik**: Browser Faskes/Admin secara otomatis mengambil posisi terbaru dari database — menjamin peta TETAP terupdate bahkan jika koneksi WebSocket bermasalah.
- **Heading tersimpan di DB**: Kolom `heading` kini disimpan di tabel `tracking_points`, memungkinkan replay rute historis yang akurat.
- **Smart Deduplication**: Sistem hanya menganimasikan marker jika posisi benar-benar berubah (threshold 0.5 meter) untuk menghindari animasi patah-patah.

#### Animasi & Presisi
- **Snap-to-Road (OSRM)**: Posisi marker secara otomatis "menempel" ke geometri jalan terdekat (radius 60m), menghilangkan GPS jitter.
- **Smooth Animation**: Marker bergerak halus menggunakan `requestAnimationFrame` dengan interpolasi *ease-in-out cubic* — bukan lompat, tapi meluncur.
- **Durasi Dinamis**: Kecepatan animasi menyesuaikan interval data yang masuk (200ms untuk simulasi, 500ms–3000ms untuk GPS asli).

#### Virtual GPS Simulator
- Rute dihitung dari OSRM berdasarkan koordinat Faskes Asal → Tujuan.
- Marker bergerak menyusuri jalan raya sungguhan setiap 200ms.
- Faskes/Admin melihat simulasi secara sinkron via Whisper + Polling.
- Ketika simulasi selesai, status rujukan otomatis berubah menjadi `ARRIVED`.

---

### 🏥 Clinical Intelligence
- **Clinical Triage Algorithm**: Penentuan prioritas pasien (Merah/Kuning/Hijau) secara otomatis berdasarkan skor GCS dan tanda vital.
- **ICD-10 WHO Autocomplete**: Pencarian diagnosa standar internasional yang cepat dan akurat.
- **Patient Journey Timeline**: Rekam jejak historis pasien yang ditampilkan secara kronologis.
- **Professional PDF Audit**: Ekspor surat rujukan dengan standar audit medis, lengkap dengan **Digital Validation QR Code**.
- **Smart RS Recommendation**: Rekomendasi RS tujuan berdasarkan jarak OSRM, ETA, dan ketersediaan bed secara real-time.

### 🔐 Enterprise Operations
- **Role-Based Access Control (RBAC)**: Admin Pusat, Admin Faskes, Driver — masing-masing dengan hak akses berbeda.
- **QR Code Handover**: Protokol serah terima pasien yang aman antar fasilitas.
- **Offline Sync & GPS Durability**: Penyimpanan koordinat di `localStorage` saat sinyal hilang, sinkronisasi otomatis saat kembali online.
- **Live Chat Konsultasi**: Komunikasi real-time antar Driver & Admin di halaman tracking menggunakan Presence Channel.
- **Audit Trail**: Pencatatan lengkap setiap perubahan data.

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Real-time** | Laravel Reverb (WebSocket) — Presence Channel + Client Whisper |
| **Database** | MySQL |
| **Frontend Build** | Vite + Bootstrap 5 |
| **Maps** | Leaflet.js + Leaflet Routing Machine |
| **Routing Engine** | OSRM (Open Source Routing Machine) |
| **GPS** | Browser Geolocation API + DeviceOrientation API |
| **Tunnel (HTTPS)** | Cloudflare Tunnel (`cloudflared`) |
| **PWA** | Service Worker + Web Manifest |

---

## 🚀 Instalasi & Setup

### Prasyarat
Pastikan sudah terinstal: **XAMPP (PHP 8.2+ & MySQL)**, **Composer**, dan **Node.js**

### 1. Clone & Install

```bash
git clone <url-repo>
cd eSIR02Laravel

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### 2. Konfigurasi `.env`

Pastikan variabel berikut terisi dengan benar:

```env
APP_URL=http://localhost:8080

REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
REVERB_HOST=localhost
REVERB_PORT=8081
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

> **Untuk akses HTTPS (wajib agar GPS bekerja di HP):** Gunakan Cloudflare Tunnel dan update `REVERB_HOST` dengan domain tunnel yang diberikan.

---

## ⚡ Menjalankan Aplikasi

### 🔵 Cara Termudah — One-Click (Windows)

```powershell
.\start_esir.ps1
```

> Skrip ini otomatis menjalankan: **MySQL → Laravel → Reverb → Vite → Cloudflare Tunnel**

---

### 🟢 Cara Manual — 4 Terminal Terpisah

| Terminal | Perintah | Fungsi |
|----------|----------|--------|
| **1** | `php artisan serve --host=0.0.0.0 --port=8080` | Laravel Web Server |
| **2** | `php artisan reverb:start` | WebSocket Server (Reverb) |
| **3** | `npm run dev` | Vite (build aset frontend) |
| **4** *(opsional)* | `.\cloudflared.exe tunnel --url http://localhost:8080` | Akses publik HTTPS via Cloudflare |

---

### 🌐 Akses Aplikasi

| Mode | URL |
|------|-----|
| **Lokal (LAN)** | `http://192.168.x.x:8080` |
| **Publik HTTPS** | URL dari terminal Cloudflare (format `https://xxxx.trycloudflare.com`) |

> ⚠️ **GPS & Geolocation** hanya berfungsi di konteks **HTTPS**. Gunakan Cloudflare Tunnel saat testing dari HP.

---

## 🗺️ Arsitektur Real-time Tracking

```
📱 HP DRIVER
  ├── GPS Hardware → posisi setiap ~1 detik
  ├── Kompas (DeviceOrientation) → arah setiap 200ms
  │
  ├──[WebSocket Whisper]──→ 📡 REVERB SERVER ──→ 🖥️ Browser Faskes/Admin
  │   (posisi + arah, 200ms)      (real-time)      (animasi marker smooth)
  │
  └──[HTTP POST]──→ 🗄️ Database (setiap 3 detik)
                         ↑
  🖥️ Browser Faskes/Admin ──[Polling GET]──→ /tracking/{id}/latest (setiap 2 detik)
```

**Prioritas update di Browser Viewer:**
1. **Whisper** (jika WebSocket berhasil) → gerakan sangat halus, <200ms
2. **Polling DB** (selalu berjalan) → jaminan update setiap 2 detik sekalipun WebSocket gagal

---

## 📂 Struktur File Penting

```
app/
├── Events/
│   └── AmbulanceLocationUpdated.php   # Event broadcast GPS ke Presence Channel
├── Http/Controllers/
│   └── TrackingController.php          # show(), updateLocation(), latestPosition()
├── Models/
│   └── TrackingPoint.php               # Model titik GPS (lat, lng, heading, recorded_at)

database/migrations/
├── ..._create_tracking_points_table.php
└── ..._add_heading_to_tracking_points_table.php  # Migrasi kolom heading (arah)

resources/views/tracking/
└── show.blade.php                      # UI peta utama (Driver + Viewer dalam satu halaman)

routes/
├── web.php                             # Route tracking.show, tracking.latest, tracking.location.update
└── channels.php                        # Otorisasi Presence Channel WebSocket

```

---

## 🔐 Otorisasi Presence Channel

Channel `referral.{id}` hanya dapat diakses oleh:
- ✅ **Admin Pusat** — akses ke semua referral
- ✅ **Driver** — hanya referral yang ia ditugaskan (`referral.driver_id === user.id`)
- ✅ **Admin Faskes** — hanya jika faskes-nya adalah pengirim atau penerima referral

---

## 📝 Changelog Terkini

### 🗓️ 6 Juli 2026 — Sesi Real-time Tracking Overhaul

| # | Perubahan | File |
|---|-----------|------|
| 1 | Tambah kolom `heading` ke tabel `tracking_points` | Migration baru |
| 2 | Simpan `heading` ke DB setiap GPS ping dari driver | `TrackingController.php` |
| 3 | Endpoint baru `GET /tracking/{id}/latest` | `TrackingController.php`, `web.php` |
| 4 | Auto-polling 2 detik untuk viewer Faskes/Admin | `show.blade.php` |
| 5 | Animasi marker dengan durasi dinamis (bukan fixed 1.5s) | `show.blade.php` |
| 6 | Smart deduplication — abaikan update jika marker < 0.5m | `show.blade.php` |
| 7 | Fix race condition `window.Echo` dengan retry loop 200ms | `show.blade.php` |
| 8 | Fix otorisasi channel driver: gunakan `driver_id` langsung | `channels.php` |
| 9 | Pass `$isAssignedDriver` dari controller ke view | `TrackingController.php` |
| 10 | Whisper menyertakan `duration` untuk sinkronisasi animasi | `show.blade.php` |

---

## 📝 Catatan Pengembangan

Proyek ini dibangun untuk memenuhi standar interoperabilitas data kesehatan nasional. Penggunaan **Presence Channels** pada Reverb memastikan tracking ambulans hanya dapat diakses oleh pihak yang berwenang. Arsitektur dual-layer (Whisper + Polling) memastikan _zero downtime_ pada tampilan peta Faskes meskipun WebSocket terputus sementara.

---

*© 2026 eSIR Project Team — Solusi Rujukan Terdepan Indonesia.*
