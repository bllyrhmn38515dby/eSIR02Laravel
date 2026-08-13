# 📦 Panduan Git Manual — eSIR02Laravel
> Upload, Pull, dan Kelola Repository GitHub tanpa GitHub Desktop

**Repository:** `https://github.com/bllyrhmn38515dby/eSIR02Laravel.git`  
**Branch Utama:** `main`  
**Branch Alternatif:** `gotoEVO`

---

## 🛠️ Prasyarat (Sekali Saja)

Pastikan Git sudah terinstal dan terkonfigurasi:

```bash
# Cek versi Git
git --version

# Set identitas Git (sekali saja)
git config --global user.name "Nama Kamu"
git config --global user.email "email@kamu.com"

# Simpan kredensial agar tidak perlu login berulang
git config --global credential.helper store
```

---

## 🚀 Alur Kerja Harian (Push Perubahan ke GitHub)

### Langkah 1: Cek Status File yang Berubah

```bash
git status
```

Output berwarna merah = belum di-stage, hijau = sudah di-stage, siap commit.

### Langkah 2: Tambahkan File ke Stage

```bash
# Tambah SEMUA file yang berubah (paling umum)
git add .

# Atau tambah file tertentu saja
git add app/Http/Controllers/ReferralController.php
git add resources/views/referrals/edit.blade.php
```

### Langkah 3: Commit dengan Pesan yang Jelas

```bash
git commit -m "feat: deskripsi singkat perubahan"

# Contoh pesan commit yang baik:
git commit -m "feat: auto-assign driver saat rujukan accepted"
git commit -m "fix: perbaiki status rujukan dari draft menjadi sent"
git commit -m "ui: tambah alert notifikasi penolakan di Mission Control"
```

> 💡 **Konvensi Pesan Commit:**
> - `feat:` → fitur baru
> - `fix:` → perbaikan bug
> - `ui:` → perubahan tampilan
> - `refactor:` → perapian kode (tanpa fitur baru)
> - `docs:` → perubahan dokumentasi

### Langkah 4: Push ke GitHub

```bash
# Push ke branch main (branch utama)
git push origin main

# Jika push pertama kali setelah clone fresh
git push -u origin main
```

---

## 📥 Pull (Ambil Perubahan Terbaru dari GitHub)

Selalu lakukan pull sebelum mulai kerja, terutama jika kolaborasi tim:

```bash
# Pull dari branch main
git pull origin main

# Pull + otomatis rebase (lebih rapi untuk hindari merge commit)
git pull --rebase origin main
```

---

## 🌿 Manajemen Branch

### Melihat Branch yang Ada

```bash
# Branch lokal saja
git branch

# Semua branch (lokal + remote)
git branch -a
```

### Berpindah Branch

```bash
# Pindah ke branch gotoEVO
git checkout gotoEVO

# Atau cara modern
git switch gotoEVO

# Kembali ke main
git switch main
```

### Membuat Branch Baru

```bash
# Buat branch baru dan langsung pindah ke sana
git checkout -b nama-branch-baru

# Contoh:
git checkout -b feature/driver-dashboard
```

### Push Branch Baru ke GitHub

```bash
git push -u origin feature/driver-dashboard
```

### Menggabungkan Branch ke Main (Merge)

```bash
# Pastikan dulu ada di branch main
git switch main

# Pull dulu supaya main up-to-date
git pull origin main

# Merge branch lain ke main
git merge feature/driver-dashboard

# Push hasilnya
git push origin main
```

---

## 🔄 Sinkronisasi Lengkap (Pull → Kerja → Push)

Urutan aman untuk menghindari konflik:

```bash
# 1. Pastikan di branch yang benar
git status

# 2. Ambil perubahan terbaru
git pull origin main

# 3. Kerjakan perubahan kode...

# 4. Stage semua perubahan
git add .

# 5. Commit
git commit -m "feat: nama fitur yang dikerjakan"

# 6. Push
git push origin main
```

---

## 🔍 Perintah Berguna Lainnya

### Melihat Riwayat Commit

```bash
# Riwayat ringkas satu baris per commit
git log --oneline

# Riwayat 10 commit terakhir
git log --oneline -10

# Riwayat dengan tampilan graph branch
git log --oneline --graph --all
```

### Membatalkan Perubahan Sebelum Commit

```bash
# Batalkan perubahan satu file (kembalikan ke versi terakhir)
git checkout -- resources/views/referrals/edit.blade.php

# Batalkan semua perubahan yang belum di-stage
git checkout -- .

# Unstage file (keluarkan dari staging area)
git reset HEAD nama-file.php
```

### Membatalkan Commit Terakhir (Tetap Simpan Perubahan)

```bash
# Undo commit terakhir, tapi perubahan file tetap ada
git reset --soft HEAD~1
```

### Melihat Perbedaan File

```bash
# Lihat perbedaan semua file yang belum di-stage
git diff

# Lihat perbedaan semua file yang sudah di-stage
git diff --staged
```

---

## ⚠️ Mengatasi Masalah Umum

### Konflik Saat Pull (Merge Conflict)

```bash
# Cek file mana yang konflik
git status

# Buka file tersebut, cari tanda <<<<<<< HEAD
# Edit secara manual — pilih kode yang benar
# Setelah selesai, stage ulang
git add nama-file-yang-konflik.php

# Selesaikan merge
git commit -m "fix: selesaikan merge conflict di nama-file"
```

### Autentikasi GitHub Gagal (403/401)

Gunakan **Personal Access Token (PAT)** sebagai pengganti password:
1. Buka `https://github.com/settings/tokens`
2. Klik **Generate new token (classic)**
3. Beri centang pada `repo`
4. Copy token tersebut
5. Gunakan token sebagai **password** saat git meminta login

```bash
# Simpan agar tidak ditanya lagi
git config --global credential.helper store
# Lalu jalankan git push sekali, masukkan username + token
```

### Push Ditolak karena Perbedaan History

```bash
# Jika remote lebih baru, pull dulu
git pull origin main --rebase
git push origin main
```

---

## 📋 Cheat Sheet Cepat

| Aksi | Perintah |
|---|---|
| Cek status file | `git status` |
| Stage semua file | `git add .` |
| Commit | `git commit -m "pesan"` |
| Push ke GitHub | `git push origin main` |
| Pull dari GitHub | `git pull origin main` |
| Lihat riwayat | `git log --oneline -10` |
| Pindah branch | `git switch nama-branch` |
| Buat branch baru | `git checkout -b nama-branch` |
| Batalkan perubahan | `git checkout -- .` |
| Undo commit terakhir | `git reset --soft HEAD~1` |

---

> 📌 **Catatan:** Semua perintah di atas dijalankan di folder proyek: `d:\githubcloning\eSIR02Laravel`  
> Buka **PowerShell** atau **Command Prompt**, lalu navigasi ke folder tersebut sebelum menjalankan perintah Git.
