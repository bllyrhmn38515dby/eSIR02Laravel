# Lampiran: Transkrip Wawancara Penggalian Kebutuhan (Requirement Gathering)

**Proyek:** eSIR (Sistem Informasi Rujukan) v2.1  
**Metode:** Wawancara Semi-Terstruktur  
**Tujuan:** Menggali kendala pada sistem rujukan berjalan dan mengidentifikasi kebutuhan fungsional serta non-fungsional untuk pengembangan eSIR 2.1.

---

## 1. Sesi Wawancara: Admin Pusat (Dinas Kesehatan / Koordinator Rujukan)

**Pewawancara:** Business Analyst (BA)  
**Narasumber:** Bapak Andi (Kepala Seksi Pelayanan Kesehatan / Admin Pusat)  
**Tanggal:** 15 April 2026

**BA:** "Selamat pagi, Pak Andi. Terima kasih atas waktunya. Terkait sistem rujukan dan pemantauan ambulans saat ini, apa kendala utama yang paling sering dikeluhkan oleh tim di pusat?"  
**Pak Andi:** "Selamat pagi. Kendala utamanya adalah kami sering kehilangan jejak ambulans setelah mereka berangkat dari faskes perujuk. Kami tidak tahu secara pasti apakah ambulans sudah di jalan, terjebak macet, atau sudah sampai di rumah sakit rujukan. Semuanya masih by phone atau WhatsApp, yang mana responsnya lambat jika sopir sedang menyetir."

**BA:** "Jadi visibilitas posisi ambulans sangat minim ya, Pak. Harapan Bapak untuk sistem eSIR yang baru seperti apa?"  
**Pak Andi:** "Betul. Saya ingin di dashboard saya, saya bisa melihat pergerakan ambulans secara *real-time* di peta. Jadi kalau ada RS rujukan yang tanya 'ambulans sampai mana?', saya bisa langsung jawab akurat. Selain itu, saya butuh rekap data waktu tempuh rujukan untuk evaluasi KPI bulanan."

**BA:** "Baik. Untuk fitur pelaporan (reporting), data apa saja yang wajib ada di laporan akhir bulan?"  
**Pak Andi:** "Harus ada data total rujukan per faskes, rata-rata waktu tempuh (response time), status rujukan (selesai, dibatalkan), dan detail sopir serta kendaraan yang bertugas."

**BA:** "Apakah Bapak membutuhkan fitur untuk memverifikasi faskes baru yang ingin mendaftar ke sistem?"  
**Pak Andi:** "Sangat butuh. Semua faskes dan sopir baru harus melalui proses persetujuan (approval) dari Admin Pusat sebelum bisa login dan menggunakan sistem."

---

## 2. Sesi Wawancara: Admin Fasilitas Kesehatan (RS/Puskesmas Perujuk)

**Pewawancara:** Business Analyst (BA)  
**Narasumber:** Ibu Siti (Admin IGD Puskesmas / Faskes Perujuk)  
**Tanggal:** 16 April 2026

**BA:** "Selamat pagi, Bu Siti. Saat Ibu hendak merujuk pasien gawat darurat ke RS lain, bagaimana proses penugasan ambulans yang berjalan saat ini?"  
**Bu Siti:** "Pagi, Mas. Saat ini saya harus lari ke ruang tunggu sopir atau telepon mereka satu-satu untuk tanya siapa yang *standby*. Terkadang ada miskomunikasi, sopirnya ternyata sedang istirahat di luar atau bawa pasien lain."

**BA:** "Itu pasti memakan waktu ya, Bu. Kalau di sistem eSIR nanti Ibu bisa melihat daftar sopir yang sedang *Available* atau *On Duty* di layar, apakah akan membantu?"  
**Bu Siti:** "Wah, itu sangat membantu! Jadi saya bisa langsung pilih sopir yang *available*, lalu sistem otomatis mengirim notifikasi penugasan ke HP mereka beserta data pasien dan RS tujuannya."

**BA:** "Terkait serah terima pasien dari faskes ke ambulans, apakah sering ada masalah pencatatan waktu?"  
**Bu Siti:** "Iya, kadang kita lupa mencatat jam berapa tepatnya pasien masuk ambulans karena panik. Kalau bisa ada sistem scan atau tombol yang cepat untuk menandakan bahwa pasien sudah diserahterimakan, itu akan meminimalisir kesalahan data."

**BA:** "Kami akan menyiapkan fitur serah terima berbasis QR Code untuk itu, Bu. Untuk notifikasi, apakah Ibu merasa perlu ada suara peringatan di dashboard saat status ambulans berubah?"  
**Bu Siti:** "Perlu sekali. Karena saya tidak selalu menatap layar komputer, notifikasi suara akan sangat membantu menyadarkan saya kalau ambulans sudah sampai di RS rujukan."

---

## 3. Sesi Wawancara: Sopir Ambulans

**Pewawancara:** Business Analyst (BA)  
**Narasumber:** Pak Budi (Sopir Ambulans)  
**Tanggal:** 16 April 2026

**BA:** "Halo Pak Budi, terima kasih sudah menyempatkan waktu. Pak, saat membawa pasien rujukan, kesulitan apa yang sering Bapak alami terkait komunikasi dengan RS tujuan atau admin?"  
**Pak Budi:** "Paling repot kalau saya sedang nyetir kencang bawa pasien kritis, lalu ditelepon terus-terusan oleh admin tanya posisi. Saya kan tidak bisa angkat telepon karena bahaya."

**BA:** "Kami berencana memasang pelacakan GPS otomatis dari HP Bapak menggunakan aplikasi eSIR. Jadi Bapak tidak perlu lapor manual lagi. Bagaimana menurut Bapak?"  
**Pak Budi:** "Bagus itu, Mas. Asalkan aplikasinya tidak bikin HP saya cepat panas atau baterainya cepat habis ya. Dan tolong tombol-tombol di aplikasinya dibuat besar-besar, biar gampang dipencet kalau lagi terburu-buru."

**BA:** "Catatan yang bagus, Pak. Nanti *user interface* untuk mobile akan disesuaikan. Lalu, untuk proses serah terima pasien di RS tujuan, biasanya bagaimana?"  
**Pak Budi:** "Biasanya saya cuma serahkan pasien ke perawat IGD, terus lapor lisan. Kadang admin puskesmas nebak-nebak saja saya sudah sampai jam berapa."

**BA:** "Kalau serah terimanya menggunakan sistem Scan QR Code (Pak Budi scan QR Code dari petugas RS, atau admin Faskes yang scan), apakah keberatan?"  
**Pak Budi:** "Sama sekali tidak keberatan. Malah enak, jadi ada bukti sah secara sistem bahwa tugas saya sudah selesai dan saya sudah serahkan pasien tepat waktu."

---

## Kesimpulan & Analisis Hasil Wawancara (Untuk Dokumen BRD)

Berdasarkan wawancara di atas, ditarik beberapa kebutuhan utama (*Requirements*):
1. **Real-time Tracking:** Sistem harus memiliki kemampuan memancarkan dan menampilkan lokasi GPS ambulans secara *real-time* ke dashboard Admin Pusat dan Faskes (menggunakan WebSocket/Laravel Reverb).
2. **Automated Dispatching:** Admin Faskes harus dapat melihat status ketersediaan sopir (*Available/On Duty*) dan memberikan penugasan langsung via sistem.
3. **QR Code Handover:** Implementasi serah terima pasien menggunakan metode Scan QR Code untuk mencatat *timestamp* yang akurat dan *tamper-proof*.
4. **Notifikasi Suara:** Dashboard web (terutama untuk Admin Faskes) harus dilengkapi notifikasi audio untuk peringatan perubahan status rujukan yang penting.
5. **Mobile-Friendly UI untuk Sopir:** Antarmuka untuk sopir ambulans harus responsif, hemat daya, dan memiliki elemen tombol yang besar (aksesibilitas tinggi).
6. **Laporan Kinerja:** Sistem harus bisa menghasilkan laporan komprehensif terkait *response time* dan performa faskes/sopir.

---

## Lampiran Tambahan: Ilustrasi Form Wawancara (Template Standar BA)

Berikut adalah ilustrasi tata letak (layout) dokumen fisik atau formulir digital yang biasa digunakan oleh *Business Analyst* saat melakukan wawancara dengan pemangku kepentingan (*stakeholders*). Form ini dapat disertakan sebelum atau sesudah transkrip lengkap di dokumen Anda.

```text
================================================================================
          FORM PENGGALIAN KEBUTUHAN SISTEM (REQUIREMENT GATHERING)
================================================================================

A. INFORMASI SESI
--------------------------------------------------------------------------------
ID Wawancara          : IV-eSIR-[   ]
Tanggal & Waktu       : ____________________________________
Lokasi / Media        : ____________________________________
Nama Pewawancara (BA) : ____________________________________

B. PROFIL NARASUMBER (STAKEHOLDER)
--------------------------------------------------------------------------------
Nama Lengkap          : ____________________________________
Jabatan / Peran       : [ ] Admin Pusat    [ ] Admin Faskes
                        [ ] Sopir Ambulans [ ] Lainnya: ___________
Instansi / Asal RS    : ____________________________________
Tugas Utama           : ____________________________________

C. DAFTAR PERTANYAAN INTI & JAWABAN
--------------------------------------------------------------------------------
1. Bagaimana Anda menjalankan proses operasional (penugasan ambulans /
   pemantauan rujukan / pelaporan) saat ini tanpa sistem eSIR 2.1?
   Jawaban:
   __________________________________________________________________________
   __________________________________________________________________________

2. Apa kendala (pain points) terbesar yang sering mengganggu efisiensi
   kerja Anda pada proses tersebut?
   Jawaban:
   __________________________________________________________________________
   __________________________________________________________________________

3. Fitur atau informasi apa yang paling Anda harapkan ada di sistem
   eSIR yang baru untuk menyelesaikan masalah tersebut?
   Jawaban:
   __________________________________________________________________________
   __________________________________________________________________________

4. [Pertanyaan Opsional / Spesifik Peran] 
   Contoh: Apakah ada kendala terkait perangkat (HP lambat/layar kecil)
   atau sinyal internet di lokasi Anda bertugas?
   Jawaban:
   __________________________________________________________________________
   __________________________________________________________________________

D. CATATAN OBSERVASI (DIISI OLEH BA)
--------------------------------------------------------------------------------
(Catat hal-hal di luar ucapan narasumber, misal: narasumber menunjukkan tumpukan
kertas form rujukan manual, koneksi internet di ruang IGD terlihat tidak stabil)
________________________________________________________________________________
________________________________________________________________________________

E. PENGESAHAN / VALIDASI
--------------------------------------------------------------------------------
Dengan ini narasumber memvalidasi bahwa informasi yang diberikan adalah benar.

Tanda Tangan Narasumber,                     Tanda Tangan Pewawancara (BA),



( _______________________ )                  ( _______________________ )
```
