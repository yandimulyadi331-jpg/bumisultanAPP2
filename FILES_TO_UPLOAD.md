# 📋 DAFTAR LENGKAP FILE UNTUK UPLOAD KE HOSTING

**Total Files:** 12 file  
**Total Size:** ~80 KB  

---

## 🔴 FILE BARU (8 file)

Copy file-file berikut dari development ke hosting:

### 1️⃣ Service Layer
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Services\QrCodeBarangService.php
TARGET:  /app/Services/QrCodeBarangService.php
SIZE:    ~5 KB
```

### 2️⃣ Controllers (Baru)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\BarangPublicController.php
TARGET:  /app/Http/Controllers/BarangPublicController.php
SIZE:    ~4 KB
```

### 3️⃣ Artisan Commands (Baru - 4 file)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Console\Commands\TestSvgQrCode.php
TARGET:  /app/Console/Commands/TestSvgQrCode.php
SIZE:    ~2 KB

SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Console\Commands\CheckBarangQr.php
TARGET:  /app/Console/Commands/CheckBarangQr.php
SIZE:    ~2 KB

SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Console\Commands\GenerateAllQrCodes.php
TARGET:  /app/Console/Commands/GenerateAllQrCodes.php
SIZE:    ~2 KB

SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Console\Commands\GetBarangHash.php
TARGET:  /app/Console/Commands/GetBarangHash.php
SIZE:    ~1 KB
```

### 4️⃣ Views (Baru)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\resources\views\fasilitas\barang\public-detail.blade.php
TARGET:  /resources/views/fasilitas/barang/public-detail.blade.php
SIZE:    ~18 KB
```

### 5️⃣ Database Migration (Baru)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\database\migrations\2025_12_12_000001_add_qr_code_to_barangs_table.php
TARGET:  /database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php
SIZE:    ~2 KB
```

---

## 🟡 FILE YANG DIMODIF (4 file)

**PENTING:** File-file ini sudah ada, tinggal di-replace:

### 1️⃣ Model
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Models\Barang.php
TARGET:  /app/Models/Barang.php
PERUBAHAN: Tambah 4 kolom di $fillable
SIZE:    ~10 KB
```

### 2️⃣ Admin Controller
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\BarangController.php
TARGET:  /app/Http/Controllers/BarangController.php
PERUBAHAN: Tambah QR generation di store/update/destroy
SIZE:    ~25 KB
```

### 3️⃣ Views (List Admin)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\resources\views\fasilitas\barang\index.blade.php
TARGET:  /resources/views/fasilitas/barang/index.blade.php
PERUBAHAN: Hapus kolom QR, eye icon tetap
SIZE:    ~12 KB
```

### 4️⃣ Routes
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\routes\web.php
TARGET:  /routes/web.php
PERUBAHAN: Tambah 3 public routes untuk QR
SIZE:    ~40 KB
```

---

## 🟢 UTILITY FILE (Optional)

### Deploy Script (Recommended)
```
SOURCE:  D:\bumisultanAPP\bumisultanAPP\deploy.php
TARGET:  /deploy.php (root folder)
SIZE:    ~2 KB
USAGE:   Akses http://domainanda.com/deploy.php?key=secret_key
HAPUS SETELAH SELESAI!
```

---

## 📚 DOKUMENTASI (Optional tapi Recommended)

Copy documentation files untuk reference:

```
START_HERE_QR_CODE.md
QUICK_DEPLOYMENT_QR_CODE.md
QR_CODE_READY_FOR_PRODUCTION.md
DOKUMENTASI_QR_CODE_BARANG.md
API_REFERENCE_QR_CODE_BARANG.md
HOSTING_UPDATE_CHECKLIST.md
UPDATE_HOSTING_SIMPLE.md
```

---

## 🚀 UPLOAD STRATEGY

### Strategy A: Via FTP (Recommended)
```
1. Open FileZilla
2. Connect ke hosting
3. Drag & drop 12 file di atas ke hosting
4. Done!
```

### Strategy B: Selective Upload (Faster)
Jika hosting sudah punya sebagian file, hanya upload yang baru:

**PASTI upload (critical):**
- QrCodeBarangService.php (BARU)
- BarangPublicController.php (BARU)
- public-detail.blade.php (BARU)
- Migration file (BARU)
- Barang.php (MODIFIED)
- BarangController.php (MODIFIED)
- index.blade.php (MODIFIED)
- web.php (MODIFIED)

**OPTIONAL (utility):**
- Artisan Commands (4 file)
- deploy.php
- Documentation files

---

## ✅ POST-UPLOAD CHECKLIST

Setelah upload semua file:

```bash
# SSH ke hosting & jalankan:
cd /path/to/application

# 1. Run migration
php artisan migrate

# 2. Generate QR codes
php artisan qrcode:generate-all

# 3. Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# 4. Verify
php artisan check:barang-qr
```

---

## 📊 File Size Reference

| Category | Files | Size |
|----------|-------|------|
| New Files | 8 | ~40 KB |
| Modified Files | 4 | ~87 KB |
| Utilities | 1 | ~2 KB |
| Documentation | 8 | ~100 KB |
| **TOTAL** | **21** | **~230 KB** |

---

## 🔍 Verification Commands

Setelah upload, jalankan ini untuk verify:

```bash
# Check QR status
php artisan check:barang-qr

# Test SVG generation
php artisan test:svg-qr

# Check routes
php artisan route:list | grep qr

# Check logs
tail -f storage/logs/laravel.log
```

---

## 🆘 Troubleshooting

### Error: File already exists (saat upload)
→ Replace/overwrite file lama dengan yang baru

### Error: Permission denied
→ Set permissions: `chmod -R 755 app/ resources/ routes/`

### QR tidak generate
→ Set permissions folder: `chmod 755 public/storage/qr_codes`

### Setelah upload tapi error
→ Check `storage/logs/laravel.log`

---

## 📝 Checklist

- [ ] Siapkan FTP credentials
- [ ] Open FTP client
- [ ] Connect ke hosting
- [ ] Create folders jika belum ada:
  - [ ] app/Services/
  - [ ] app/Console/Commands/
  - [ ] public/storage/qr_codes/
- [ ] Upload 12 file (atau 8 file baru + 4 modified)
- [ ] SSH & run `php artisan migrate`
- [ ] SSH & run `php artisan qrcode:generate-all`
- [ ] SSH & run cache clearing commands
- [ ] Test di browser: `/fasilitas/asset`
- [ ] Test QR code: `/barang/qr/{hash}`
- [ ] Check logs untuk errors
- [ ] Hapus deploy.php (kalau pakai)

---

## 📞 Need Help?

**Jika stuck:**
1. Check HOSTING_UPDATE_CHECKLIST.md
2. Check UPDATE_HOSTING_SIMPLE.md
3. Check QUICK_DEPLOYMENT_QR_CODE.md
4. Check error logs: `storage/logs/laravel.log`

---

**Date:** 2025-12-12  
**Ready:** ✅ All files prepared  
**Total Size:** ~230 KB  
**Upload Time:** 5-15 minutes (tergantung cara)
