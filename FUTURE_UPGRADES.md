# Rencana Pengembangan eSIR Selanjutnya (Future Upgrades)

Dokumen ini berisi daftar ide dan rencana pengembangan fitur untuk proyek Sistem Informasi Rujukan (eSIR) di masa mendatang berdasarkan diskusi pada 17 Juni 2026.

## 1. 🗺️ Integrasi Peta Real-Time (Live GPS Tracking)
- **Teknologi:** Leaflet.js, OpenStreetMap, Laravel Reverb (WebSockets).
- **Fungsi:** Menampilkan posisi ambulans secara langsung (live) di atas peta bagi Admin Faskes dan Admin Pusat.
- **Tujuan:** Memudahkan Faskes penerima untuk memantau estimasi waktu kedatangan pasien secara visual.

## 2. 📊 Dashboard Analytics Tingkat Lanjut
- **Teknologi:** Chart.js atau ApexCharts.
- **Fungsi:** Menampilkan visualisasi data interaktif pada halaman `/dashboard`.
- **Tujuan:** Menyajikan laporan tren rujukan bulanan, persentase rujukan diterima/ditolak, dan rasio penggunaan ranjang (*bed capacity*) untuk keputusan manajerial yang lebih baik.

## 3. 📱 Progressive Web App (PWA) & Push Notifications
- **Teknologi:** Web Manifest, Service Workers, Web Push API.
- **Fungsi:** Mengubah aplikasi web menjadi bisa di-*install* di HP dan mengirimkan notifikasi *push*.
- **Tujuan:** Memastikan dokter jaga dan *driver* ambulans mendapatkan notifikasi rujukan darurat secara instan, meskipun browser sedang tidak dibuka.

## 4. ⚡ Refaktorisasi UI dengan Livewire 3 / Alpine.js
- **Teknologi:** Laravel Livewire 3 atau Alpine.js (TALL Stack).
- **Fungsi:** Mengubah beberapa komponen statis menjadi interaktif tanpa perlu *reload* halaman penuh.
- **Tujuan:** Mempercepat waktu muat halaman dan memberikan *User Experience* (UX) yang lebih halus (*Single Page Application feel*).

## 5. 🔌 API Terbuka untuk Integrasi SIMRS Faskes
- **Teknologi:** Laravel Sanctum (API Authentication).
- **Fungsi:** Membuat *endpoint* API khusus untuk mesin ke mesin (M2M).
- **Tujuan:** Memungkinkan Rumah Sakit/Faskes menyinkronkan data ketersediaan tempat tidur mereka secara otomatis dari SIMRS internal ke eSIR, tanpa perlu *update* manual.
