# 🚀 UPDATE KE HOSTING - PANDUAN LENGKAP

**Last Updated:** 2025-12-12  
**Status:** Ready to Deploy ✅  
**Estimated Time:** 5-15 minutes  

---

## 🎯 Pilihan Cepat

### **👈 Pilih Cara Yang Paling Gampang:**

| Cara | Waktu | Difficulty | Baca File |
|------|-------|-----------|-----------|
| 🥇 Deploy Script | 2 min | ⭐ | [Baca Cara #1](#-cara-1-deploy-script) |
| 🥈 FTP Upload | 10 min | ⭐⭐ | [Baca Cara #2](#-cara-2-via-ftp) |
| 🥉 cPanel | 15 min | ⭐⭐ | [Baca Cara #3](#-cara-3-via-cpanel) |
| PRO SSH | 5 min | ⭐⭐⭐ | [Baca Cara #4](#-cara-4-via-ssh) |

👉 **REKOMENDASI UNTUK PEMULA:** Cara #1 atau #2

---

## 📚 Dokumentasi Terkait

Sebelum mulai, baca salah satu dari file-file ini:

- **UPDATE_HOSTING_SIMPLE.md** ← Mulai dari sini (paling simple!)
- **COPY_PASTE_GUIDE.md** ← Jika pakai FTP
- **FILES_TO_UPLOAD.md** ← Daftar lengkap file
- **HOSTING_UPDATE_CHECKLIST.md** ← Checklist lengkap

---

## 🥇 CARA #1: DEPLOY SCRIPT (PALING MUDAH)

**Time:** 2 minutes  
**Difficulty:** ⭐ (Sangat Mudah)  

### Langkah-Langkah:

#### Step 1: Upload File `deploy.php`
```
File lokal:  D:\bumisultanAPP\bumisultanAPP\deploy.php
Ke hosting:  /deploy.php  (root folder aplikasi)
```

Gunakan FTP atau cPanel File Manager

#### Step 2: Buka Browser
```
http://bumisultanapp.com/deploy.php?key=bumisultan_deploy_2025
```

#### Step 3: Tunggu Proses Selesai
Script akan otomatis:
- ✅ Cek semua file ada
- ✅ Clear cache
- ✅ Clear views
- ✅ Verify migrations

#### Step 4: Hapus `deploy.php` (Penting!)
Setelah selesai, hapus file `deploy.php` dari hosting untuk security.

**✅ SELESAI!**

---

## 🥈 CARA #2: VIA FTP (PALING UMUM)

**Time:** 10 minutes  
**Difficulty:** ⭐⭐ (Mudah)  

### Yang Dibutuhkan:
- FileZilla (free): https://filezilla-project.org/
- FTP Credentials dari hosting:
  - Host
  - Username
  - Password

### Langkah-Langkah:

#### Step 1: Download & Buka FileZilla
1. Download dari https://filezilla-project.org/
2. Extract & Run (tidak perlu install)

#### Step 2: Isi Data FTP
Di FileZilla, isi:
```
Host: ftp.bumisultanapp.com
Username: ftp_username_anda
Password: ftp_password_anda
Port: 21
```
*(Ganti dengan data asli dari hosting provider)*

Klik **"Quickconnect"**

#### Step 3: Navigate ke Folder Aplikasi
- **Kanan (Remote):** Go to root folder aplikasi
  - Biasanya: `/public_html/bumisultanAPP/`
  - Atau: `/www/bumisultanAPP/`

#### Step 4: Upload File-File Berikut

Copy file-file ini dari local ke remote:

**BATCH 1: New Files**
```
app/Services/QrCodeBarangService.php
app/Http/Controllers/BarangPublicController.php
app/Console/Commands/TestSvgQrCode.php
app/Console/Commands/CheckBarangQr.php
app/Console/Commands/GenerateAllQrCodes.php
app/Console/Commands/GetBarangHash.php
resources/views/fasilitas/barang/public-detail.blade.php
database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php
```

**BATCH 2: Modified Files (REPLACE)**
```
app/Models/Barang.php
app/Http/Controllers/BarangController.php
resources/views/fasilitas/barang/index.blade.php
routes/web.php
```

#### Step 5: SSH & Run Commands
Jika punya akses SSH:

```bash
ssh user@hosting.com
cd /path/to/bumisultanAPP

php artisan migrate
php artisan qrcode:generate-all
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Jika tidak punya SSH, skip step ini (tapi disarankan untuk run).

**✅ SELESAI!**

---

## 🥉 CARA #3: VIA cPANEL

**Time:** 15 minutes  
**Difficulty:** ⭐⭐ (Mudah)  

### Langkah-Langkah:

#### Step 1: Login cPanel
```
https://hosting.com:2083
Username: cpanel_username
Password: cpanel_password
```

#### Step 2: Buka File Manager
Cari "File Manager" → Klik

#### Step 3: Navigate ke Aplikasi
Klik folder struktur:
```
public_html/
  └─ bumisultanAPP/
     ├─ app/
     ├─ resources/
     ├─ database/
     └─ ...
```

#### Step 4: Upload Files
Gunakan tombol "Upload" atau Drag & Drop:

**BATCH 1: Create Folders (jika belum ada)**
- Klik kanan → Create Folder
- Buat: `app/Services/`, `app/Console/Commands/`, dll

**BATCH 2: Upload File-File**
1. Drag & drop dari local ke cPanel File Manager
2. Atau gunakan tombol "Upload"

#### Step 5: Run Commands
Jika punya akses Terminal (di cPanel):
```bash
cd /home/username/public_html/bumisultanAPP

php artisan migrate
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

**✅ SELESAI!**

---

## 🎯 CARA #4: VIA SSH (UNTUK ADVANCED)

**Time:** 5 minutes  
**Difficulty:** ⭐⭐⭐ (Advanced)  

```bash
# Connect ke hosting
ssh user@hosting.com

# Go to folder
cd /home/user/public_html/bumisultanAPP

# Git pull (jika pakai git)
git pull origin main

# Atau copy files manually via SFTP

# Run commands
php artisan migrate
php artisan qrcode:generate-all
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Verify
php artisan check:barang-qr
```

**✅ SELESAI!**

---

## ✅ VERIFICATION SETELAH UPLOAD

Buka browser dan test:

### 1. Admin Panel
```
http://bumisultanapp.com/fasilitas/asset
```
- ✅ Akses berhasil
- ✅ List barang tampil
- ✅ Ikon mata ada di kolom action

### 2. Public QR Detail
```
http://bumisultanapp.com/barang/qr/{hash}
```
*Ganti {hash} dengan QR hash dari database*

- ✅ Halaman detail tampil
- ✅ SVG QR code muncul
- ✅ Mobile responsive
- ✅ Print button works

### 3. JSON API
```
http://bumisultanapp.com/api/barang/qr/{hash}
```

- ✅ Return JSON data
- ✅ Status 200 OK

---

## 🚨 JIKA ADA ERROR

### Error 1: File not found (404)
**Penyebab:** File belum ter-upload atau path salah  
**Solusi:**
1. Cek file sudah ada di hosting via FTP
2. Verify path folder benar
3. Re-upload file

### Error 2: Permission denied
**Penyebab:** Folder permissions terlalu restrictive  
**Solusi:**
```bash
chmod 755 app/
chmod 755 resources/views/
chmod 755 database/
chmod 755 public/storage/qr_codes/
```

### Error 3: QR tidak generate
**Penyebab:** Directory permission atau storage issue  
**Solusi:**
```bash
mkdir -p public/storage/qr_codes
chmod 755 public/storage/qr_codes
php artisan qrcode:generate-all
```

### Error 4: Cache masih lama (stale)
**Penyebab:** Cache belum di-clear  
**Solusi:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Error 5: Lihat detail error
Buka file:
```
storage/logs/laravel.log
```

Via FTP: Download file  
Via cPanel: Buka di File Manager  
Via SSH: `tail -f storage/logs/laravel.log`

---

## 📋 PRE-UPLOAD CHECKLIST

Sebelum upload, pastikan:

- [ ] Punya FTP/SSH/cPanel credentials
- [ ] Punya akses ke hosting
- [ ] Hosting support PHP 8.2+ & Laravel
- [ ] Storage folder writable (`public/storage/`)
- [ ] Cukup disk space (~200 MB)

---

## 📊 FILE SUMMARY

| Tipe | Count | Size |
|------|-------|------|
| New Files | 8 | 40 KB |
| Modified Files | 4 | 87 KB |
| Total | 12 | 127 KB |

---

## ⏱️ TIMELINE

| Step | Time | Task |
|------|------|------|
| 1 | 5 min | Upload files via FTP |
| 2 | 2 min | SSH & run migrate |
| 3 | 2 min | SSH & run cache clear |
| 4 | 3 min | SSH & generate QR |
| 5 | 3 min | Test di browser |
| **TOTAL** | **15 min** | **DONE** |

---

## 📞 NEED HELP?

**Jika masih bingung, baca file ini sesuai cara pilihan:**

- Cara #1 → `deploy.php` in root folder
- Cara #2 → `COPY_PASTE_GUIDE.md` 
- Cara #3 → `UPDATE_HOSTING_SIMPLE.md`
- Cara #4 → `HOSTING_UPDATE_CHECKLIST.md`

**Atau contact hosting support untuk:**
- FTP credentials
- SSH access
- Folder root path aplikasi
- PHP version
- Database info

---

## 🎊 SETELAH BERHASIL

Congratulations! 🎉

Fitur QR Code sudah live di hosting.

**Next steps:**
1. Share public links dengan users
2. Test pada mobile devices
3. Monitor logs untuk errors
4. Gather feedback
5. Plan Phase 2 enhancements

---

**Status:** Ready ✅  
**Files:** 12 prepared  
**Documentation:** Complete  
**Support:** Available  

**You can do this! Let's go! 🚀**

---

Jika ada pertanyaan, silakan tanya. Siap membantu! 👍
