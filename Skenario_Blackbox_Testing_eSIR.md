# Skenario Blackbox Testing - Aplikasi eSIR 2.1 (Berdasarkan Prioritas)

Dokumen ini memilah 19 skenario pengujian *Blackbox Testing* sebelumnya menjadi 3 tingkat prioritas. Pengujian **Prioritas Tinggi** wajib diprioritaskan karena mewakili alur bisnis inti (*Critical Path*) dari sistem eSIR.

---

## 🔴 PRIORITAS TINGGI (CRITICAL PATH)
*Bagian ini menguji alur utama aplikasi: mulai dari akses masuk, pembuatan rujukan, penugasan ambulans, pelacakan real-time, hingga serah terima.*

| ID Test | Modul | Skenario Pengujian | Langkah-Langkah Singkat | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **P1-01** | Autentikasi | **Login dengan Kredensial Valid** | Login menggunakan akun Admin Pusat, Admin Faskes, dan Driver. | Berhasil masuk ke dashboard masing-masing sesuai hak akses (*role*). | |
| **P1-02** | Manajemen Rujukan | **Pembuatan Rujukan Baru Berhasil** | Admin Faskes mengisi seluruh form rujukan wajib dan submit. | Rujukan tersimpan dan status menjadi "Menunggu Persetujuan". | |
| **P1-03** | Manajemen Rujukan | **Persetujuan & Penugasan Ambulans** | Admin Pusat menyetujui rujukan dan memilih driver. | Status menjadi "Disetujui". Driver terpilih menerima notifikasi tugas. | |
| **P1-04** | Pelacakan | **Update Status Keberangkatan (Driver)** | Driver mengubah status rujukan menjadi "Menuju Faskes Perujuk". | Status rujukan terupdate dan waktu keberangkatan tercatat di sistem. | |
| **P1-05** | Pelacakan | **Serah Terima Pasien (QR Code)** | Faskes tujuan menscan QR Code *handover* dari layar perangkat Driver. | Status rujukan menjadi "Selesai". Waktu kedatangan/serah terima terkunci permanen. | |
| **P1-06** | Pelacakan | **Pengujian Sinkronisasi *Real-Time*** | Driver mengubah status sambil Admin Pusat membuka layar Dashboard secara bersamaan. | Layar Admin Pusat langsung menampilkan perubahan status dalam hitungan detik **tanpa perlu *refresh* halaman** (Reverb berfungsi). | |

---

## 🟡 PRIORITAS MENENGAH (KEAMANAN, VALIDASI & ALTERNATIF ALUR)
*Skenario ini menguji batas-batas sistem, keamanan data, pelaporan administratif, dan alur operasional sekunder.*

| ID Test | Modul | Skenario Pengujian | Langkah-Langkah Singkat | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **P2-01** | Autentikasi | **Pencegahan Akses Ilegal** | Mencoba akses langsung ke `/dashboard` tanpa login, atau Driver mencoba akses URL Admin. | Sistem me-redirect ke login atau memunculkan pesan error 403 Forbidden. | |
| **P2-02** | Manajemen Rujukan | **Validasi Form Rujukan Kosong** | Admin Faskes mensubmit rujukan tanpa mengisi data pasien wajib. | Form ditolak, muncul pesan error merah di bawah kolom yang kosong. | |
| **P2-03** | Manajemen Rujukan | **Penolakan Rujukan oleh Admin Pusat** | Admin Pusat menolak rujukan dengan memasukkan alasan penolakan. | Status menjadi "Ditolak", alasan penolakan dapat dilihat oleh Admin Faskes. | |
| **P2-04** | Autentikasi | **Gagal Login (Kredensial Salah)** | Login dengan email/password acak yang tidak terdaftar. | Ditolak masuk, muncul pesan peringatan gagal login. | |
| **P2-05** | Pelacakan | **Halaman *Live Tracking* Publik** | Mengakses link pelacakan unik dari sebuah rujukan aktif. | Peta/Timeline tampil akurat sesuai update status terakhir driver. | |
| **P2-06** | Pelaporan | **Ekspor Laporan (PDF/Excel)** | Admin Pusat memfilter data bulan ini lalu klik tombol Export Excel/PDF. | File terunduh, formatnya rapi, dan isinya sama dengan data di layar. | |
| **P2-07** | Pelaporan | **Filter Laporan via Tanggal** | Mengubah *date range* dari form laporan. | Tabel otomatis hanya menampilkan rujukan yang terjadi pada tanggal tersebut. | |

---

## 🟢 PRIORITAS RENDAH (UI/UX & PENYEMPURNAAN)
*Skenario ini lebih menitikberatkan pada kenyamanan pengguna dan metrik tampilan yang tidak menghentikan operasional utama jika terjadi sedikit gangguan.*

| ID Test | Modul | Skenario Pengujian | Langkah-Langkah Singkat | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **P3-01** | UI/UX | **Pengujian *Responsiveness* Mobile** | Buka halaman utama dan daftar tugas Driver di layar *smartphone*. | Layout menyesuaikan layar, tabel tidak terpotong, tombol mudah ditekan jari. | |
| **P3-02** | UI/UX | **Feedback Visual Interaktif** | Mengklik tombol hapus atau ubah status. | Muncul konfirmasi dialog (seperti *SweetAlert*) dan *toast notification* sukses setelahnya. | |
| **P3-03** | Pelaporan | **Akurasi Metrik Dashboard** | Membandingkan angka "Total Rujukan" di atas layar dengan data aktual tabel. | Jumlah angka yang tampil di kartu metrik sesuai dengan total baris data yang ada. | |
| **P3-04** | UI/UX | **Navigasi (*Broken Link* Test)** | Menekan semua menu sidebar satu per satu. | Semua link berfungsi dan mengarah ke halaman yang benar (tidak ada error 404). | |
