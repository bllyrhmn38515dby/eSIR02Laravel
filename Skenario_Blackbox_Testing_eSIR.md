# Skenario Blackbox Testing - Aplikasi eSIR 2.1 (Berdasarkan Prioritas)

Dokumen ini memilah 20 skenario pengujian *Blackbox Testing* sebelumnya menjadi 3 tingkat prioritas. Pengujian **Prioritas Tinggi** wajib diprioritaskan karena mewakili alur bisnis inti (*Critical Path*) dari sistem eSIR.

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

## 🟢 PRIORITAS TAMBAHAN (KEAMANAN SESI, EDGE CASES & MANAJEMEN USER)
*Skenario ini menitikberatkan pada keamanan sesi pengguna, pengujian alur operasional sekunder, serta manajemen pengguna.*

| ID Test | Modul | Skenario Pengujian | Langkah-Langkah Singkat | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **P3-01** | Autentikasi | **Logout & Proteksi Sesi** | User yang sudah login menekan tombol "Logout", lalu mencoba menekan tombol back browser untuk kembali ke halaman sebelumnya. | Sesi berakhir, user diarahkan ke halaman login. Halaman sebelumnya tidak dapat diakses kembali tanpa login ulang. | |
| **P3-02** | Autentikasi | **Session Timeout Otomatis** | User login, lalu membiarkan sistem tidak aktif melebihi batas waktu sesi (misal 30 menit), kemudian mencoba melakukan aksi. | Sistem otomatis mengakhiri sesi dan me-redirect ke halaman login dengan pesan "Sesi Anda telah berakhir". | |
| **P3-03** | Manajemen Rujukan | **Edit Rujukan Sebelum Disetujui** | Admin Faskes membuka rujukan berstatus "Menunggu Persetujuan" dan mengubah data pasien atau tujuan faskes, lalu menyimpan perubahan. | Data rujukan berhasil diperbarui. Riwayat perubahan (log) tercatat, dan status tetap "Menunggu Persetujuan". | |
| **P3-04** | Manajemen Rujukan | **Pembatalan Rujukan oleh Admin Faskes** | Admin Faskes membatalkan rujukan yang masih berstatus "Menunggu Persetujuan" dengan menekan tombol "Batalkan Rujukan" dan mengisi alasan. | Status rujukan berubah menjadi "Dibatalkan". Admin Pusat menerima notifikasi pembatalan. Driver tidak menerima penugasan. | |
| **P3-05** | Pelacakan | **Driver Menandai Pasien Tiba di Faskes Tujuan** | Driver menekan tombol "Pasien Tiba di Tujuan" setelah sampai di faskes perujuk, sebelum serah terima QR Code dilakukan. | Status rujukan diperbarui menjadi "Tiba di Tujuan". Waktu kedatangan tercatat di sistem. Admin Pusat dan Faskes menerima notifikasi. | |
| **P3-06** | Manajemen User | **Penambahan Akun Driver Baru oleh Admin Pusat** | Admin Pusat membuka menu Manajemen User, mengisi formulir data driver baru (nama, email, nomor kendaraan) dan menyimpan. | Akun driver berhasil dibuat. Driver menerima email aktivasi. Akun muncul di daftar driver dengan status "Aktif". | |
| **P3-07** | Pelaporan | **Validasi Laporan dengan Data Kosong (Tanpa Rujukan)** | Admin Pusat memfilter laporan pada rentang tanggal yang tidak memiliki data rujukan sama sekali, lalu mengekspor laporan. | Sistem menampilkan pesan "Tidak ada data untuk periode ini". Tombol Export tetap dapat diklik namun menghasilkan file kosong atau pesan notifikasi. | |
