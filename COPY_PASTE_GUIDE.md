# 📋 COPY-PASTE FILE PATHS UNTUK UPLOAD

## 🔴 FILE YANG HARUS DI-UPLOAD (12 file)

Jika pakai FTP, buka dua window:
- **Kiri:** Folder lokal di development
- **Kanan:** Folder di hosting

Copy file-file berikut:

---

## NEW FILES (8 file) - PASTI UPLOAD

### 1. Service Layer
```
app/Services/QrCodeBarangService.php
```

### 2. Public Controller
```
app/Http/Controllers/BarangPublicController.php
```

### 3. Artisan Commands (4 file)
```
app/Console/Commands/TestSvgQrCode.php
app/Console/Commands/CheckBarangQr.php
app/Console/Commands/GenerateAllQrCodes.php
app/Console/Commands/GetBarangHash.php
```

### 4. Public View
```
resources/views/fasilitas/barang/public-detail.blade.php
```

### 5. Database Migration
```
database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php
```

---

## MODIFIED FILES (4 file) - REPLACE

```
app/Models/Barang.php
app/Http/Controllers/BarangController.php
resources/views/fasilitas/barang/index.blade.php
routes/web.php
```

---

## UTILITY (1 file) - OPTIONAL

```
deploy.php  (untuk automation, hapus setelah pakai)
```

---

## TOTAL = 13 file

---

## 🚀 CARA CEPAT (CTRL+C PASTE)

Jika pakai FTP, bisa copy-paste path satu per satu:

```
Batch 1: Service & Commands
- app/Services/QrCodeBarangService.php
- app/Http/Controllers/BarangPublicController.php
- app/Console/Commands/TestSvgQrCode.php
- app/Console/Commands/CheckBarangQr.php
- app/Console/Commands/GenerateAllQrCodes.php
- app/Console/Commands/GetBarangHash.php

Batch 2: Views & Routes
- resources/views/fasilitas/barang/public-detail.blade.php
- resources/views/fasilitas/barang/index.blade.php
- routes/web.php

Batch 3: Models & Database
- app/Models/Barang.php
- app/Http/Controllers/BarangController.php
- database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php

Batch 4: Utility (Optional)
- deploy.php
```

---

## 📤 STEP-BY-STEP UPLOAD (Via FileZilla)

### 1. Open FileZilla
- Download: https://filezilla-project.org/
- Extract & Run

### 2. Connect ke Hosting
```
Host: ftp.yourdomain.com
Username: ftp_username
Password: ftp_password
Port: 21
Quick Connect!
```

### 3. Navigate Folder
- **Kiri (Local):** `D:\bumisultanAPP\bumisultanAPP\`
- **Kanan (Remote):** `/public_html/` atau root aplikasi

### 4. Create Folder Structure (Jika Belum Ada)
Di **Kanan (Remote)**, create:
```
app/Services/
app/Http/Controllers/
app/Console/Commands/
resources/views/fasilitas/barang/
database/migrations/
```

### 5. Drag & Drop File
Dari **Kiri** ke **Kanan**:

**Batch 1:**
- Drag `app/Services/QrCodeBarangService.php` → drop ke `app/Services/`
- Drag `app/Http/Controllers/BarangPublicController.php` → drop ke `app/Http/Controllers/`
- Drag `app/Console/Commands/*.php` (4 file) → drop ke `app/Console/Commands/`

**Batch 2:**
- Drag `resources/views/fasilitas/barang/public-detail.blade.php` → drop ke folder
- Drag `resources/views/fasilitas/barang/index.blade.php` → REPLACE
- Drag `routes/web.php` → REPLACE

**Batch 3:**
- Drag `app/Models/Barang.php` → REPLACE
- Drag `app/Http/Controllers/BarangController.php` → REPLACE
- Drag `database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php` → drop

**Batch 4 (Optional):**
- Drag `deploy.php` → drop ke root

### 6. Verify Upload
- Cek di FileZilla bahwa semua file sudah ada
- Lihat di **Kanan** file list-nya

---

## ✅ AFTER UPLOAD

SSH ke hosting & jalankan:

```bash
cd /path/to/bumisultanAPP

# 1. Run Migration
php artisan migrate

# 2. Generate QR Codes
php artisan qrcode:generate-all

# 3. Clear Cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Verify
php artisan check:barang-qr
```

---

## 🔍 CHECK FILE DI HOSTING

Setelah upload, verify dengan:

```bash
# SSH command untuk check:
ls -la app/Services/QrCodeBarangService.php
ls -la app/Http/Controllers/BarangPublicController.php
ls -la app/Console/Commands/
ls -la resources/views/fasilitas/barang/public-detail.blade.php
ls -la database/migrations/2025_12_12_000001_*
```

---

## 📊 FILE SUMMARY

| File | Type | Size |
|------|------|------|
| QrCodeBarangService.php | NEW | 5 KB |
| BarangPublicController.php | NEW | 4 KB |
| TestSvgQrCode.php | NEW | 2 KB |
| CheckBarangQr.php | NEW | 2 KB |
| GenerateAllQrCodes.php | NEW | 2 KB |
| GetBarangHash.php | NEW | 1 KB |
| public-detail.blade.php | NEW | 18 KB |
| Migration file | NEW | 2 KB |
| Barang.php | MODIFIED | 10 KB |
| BarangController.php | MODIFIED | 25 KB |
| index.blade.php | MODIFIED | 12 KB |
| web.php | MODIFIED | 40 KB |
| deploy.php | UTIL | 2 KB |
| **TOTAL** | | **126 KB** |

---

## 🆘 JIKA ERROR

### Folder tidak ada
```bash
mkdir -p app/Services
mkdir -p app/Http/Controllers
mkdir -p app/Console/Commands
mkdir -p resources/views/fasilitas/barang
mkdir -p public/storage/qr_codes
chmod 755 public/storage/qr_codes
```

### File tidak terdeteksi
- Cek spelling path
- Cek file sudah ter-upload
- Refresh FileZilla (F5)

### Permission error
```bash
chmod 755 app/
chmod 755 resources/views/
chmod 755 database/
```

---

## ✨ TIPS

1. **Upload sedikit demi sedikit** - Jangan sekaligus 13 file
2. **Verify setiap batch** - Pastikan file sudah ada
3. **Use SFTP** (port 22) - Lebih aman dari FTP (port 21)
4. **Backup dulu** - Download file lama sebelum replace
5. **Keep logs** - Download `storage/logs/laravel.log` untuk debug

---

**Good luck! You got this! 💪**

Upload time: ~5-10 minutes  
Status: Ready! ✅
