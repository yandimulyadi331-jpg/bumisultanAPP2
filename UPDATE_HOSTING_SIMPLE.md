# 🔗 UPDATE KE HOSTING - QUICK GUIDE

## Pilih Cara Yang Paling Mudah Untuk Anda:

---

## **🥇 CARA #1: Via Deploy Script (PALING MUDAH)**

**Setup Time:** 2 menit  
**Difficulty:** ⭐ Sangat Mudah

### Langkah:
1. **Upload file `deploy.php`** ke hosting (ke folder root aplikasi)

2. **Buka browser dan akses:**
   ```
   http://bumisultanapp.com/deploy.php?key=bumisultan_deploy_2025
   ```

3. **Tunggu proses selesai** - Script akan:
   - ✅ Cek semua file
   - ✅ Clear cache
   - ✅ Clear views
   - ✅ Verify database

4. **Hapus file `deploy.php`** setelah selesai (untuk security)

**✅ SELESAI!** Aplikasi sudah update.

---

## **🥈 CARA #2: Via FTP (PALING UMUM)**

**Setup Time:** 10 menit  
**Difficulty:** ⭐⭐ Mudah

### Yang Anda Butuhkan:
- FTP Client (FileZilla gratis, atau WinSCP)
- FTP Username & Password (dari hosting provider)
- FTP Server Address (dari hosting provider)

### Langkah:
1. **Download & Buka FileZilla**
   - Download dari: https://filezilla-project.org/

2. **Isi data FTP:**
   ```
   Host: ftp.bumisultanapp.com (atau IP dari hosting)
   Username: ftp_username
   Password: ftp_password
   Port: 21
   ```
   *(Tanya hosting provider untuk data ini)*

3. **Klik "Quickconnect"** - Tunggu connect

4. **Navigate ke folder aplikasi:**
   - Biasanya: `/public_html/bumisultanAPP/` atau `/www/bumisultanAPP/`

5. **Upload file-file berikut:**
   - Dari folder local (`D:\bumisultanAPP\bumisultanAPP\`) 
   - Ke folder remote (hosting)
   
   **File yang perlu di-upload:**
   ```
   app/Services/QrCodeBarangService.php
   app/Http/Controllers/BarangPublicController.php
   app/Http/Controllers/BarangController.php
   app/Models/Barang.php
   resources/views/fasilitas/barang/index.blade.php
   resources/views/fasilitas/barang/public-detail.blade.php
   routes/web.php
   database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php
   ```

6. **Setelah upload, SSH ke hosting & jalankan:**
   ```bash
   php artisan migrate
   php artisan cache:clear
   php artisan view:clear
   ```
   *(Atau skip jika tidak punya SSH)*

**✅ SELESAI!**

---

## **🥉 CARA #3: Via cPanel File Manager**

**Setup Time:** 15 menit  
**Difficulty:** ⭐⭐ Mudah

### Langkah:
1. **Login ke cPanel hosting** (biasanya di port 2083)
   ```
   https://hosting.com:2083/
   ```

2. **Cari "File Manager"** - Klik

3. **Navigate ke folder aplikasi** (biasanya `/public_html/`)

4. **Upload file-file:**
   - Klik tombol "Upload"
   - Pilih file-file dari list di atas
   - Drag & drop juga bisa

5. **Untuk folder baru:**
   - Klik kanan → Create Folder
   - Buat folder: `app/Services/` dll (jika belum ada)

**✅ SELESAI!**

---

## **🎯 CARA #4: Via SSH (UNTUK PRO)**

**Setup Time:** 5 menit  
**Difficulty:** ⭐⭐⭐ Advanced

```bash
# Connect ke hosting
ssh user@hosting.com

# Go to folder aplikasi
cd /home/user/public_html/bumisultanAPP

# Git pull (jika pakai git)
git pull origin main

# Atau copy file-file manual

# Run commands
php artisan migrate
php artisan cache:clear
php artisan view:clear
php artisan qrcode:generate-all
```

**✅ SELESAI!**

---

## 📞 Dapatkan Data Hosting Anda

**Jika belum punya FTP/SSH credentials, tanya hosting provider:**

```
Contact Info Yang Dibutuhkan:
□ FTP Host/Server Address
□ FTP Username
□ FTP Password
□ FTP Port (default 21)
□ SSH Host
□ SSH Username
□ SSH Password
□ cPanel URL
□ cPanel Username
□ cPanel Password
□ Root folder path aplikasi
```

---

## ✅ Verifikasi Setelah Update

**Buka browser dan test:**

1. **Admin panel barang:**
   ```
   http://bumisultanapp.com/fasilitas/asset
   ```
   → Harus bisa akses, ikon mata ada

2. **Halaman publik QR:**
   ```
   http://bumisultanapp.com/barang/qr/{hash}
   ```
   → Harus bisa lihat detail barang + QR code

3. **JSON API:**
   ```
   http://bumisultanapp.com/api/barang/qr/{hash}
   ```
   → Harus return JSON data

---

## 🚨 Jika Ada Error

**1. File not found (404)**
   - Cek path folder sudah benar
   - Cek file sudah ter-upload

**2. Permission denied**
   ```bash
   chmod 755 public/storage/qr_codes
   ```

**3. QR tidak muncul**
   ```bash
   php artisan qrcode:generate-all
   ```

**4. Cache masih lama**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

**5. Lihat error log:**
   - FTP: Download `storage/logs/laravel.log`
   - cPanel: View di File Manager
   - Browser console: Press F12 → Console tab

---

## 📚 Dokumentasi Lengkap

Setelah update, baca file-file ini untuk detail:

- **QUICK_DEPLOYMENT_QR_CODE.md** - Setup & cara pakai
- **QR_CODE_READY_FOR_PRODUCTION.md** - Features & API
- **DOKUMENTASI_QR_CODE_BARANG.md** - Technical details
- **HOSTING_UPDATE_CHECKLIST.md** - Checklist lengkap

---

## 🎊 SUMMARY

| Cara | Waktu | Difficulty | Best For |
|------|-------|-----------|----------|
| Deploy Script | 2 min | ⭐ | Cepat & otomatis |
| FTP | 10 min | ⭐⭐ | Umum & aman |
| cPanel | 15 min | ⭐⭐ | User-friendly |
| SSH | 5 min | ⭐⭐⭐ | Developer |

**Rekomendasi:** Gunakan **Deploy Script** kalau baru pertama kali!

---

**Date:** 2025-12-12  
**Status:** Ready to deploy ✅  
**Support:** Penuh tersedia 👍
