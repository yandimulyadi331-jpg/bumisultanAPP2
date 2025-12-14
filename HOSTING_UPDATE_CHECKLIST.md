# 📤 CHECKLIST UPDATE KE HOSTING

## File-File yang Perlu Di-Upload

### 🔴 FILE BARU (Upload)
```
✅ app/Services/QrCodeBarangService.php
✅ app/Http/Controllers/BarangPublicController.php
✅ app/Console/Commands/TestSvgQrCode.php
✅ app/Console/Commands/CheckBarangQr.php
✅ app/Console/Commands/GenerateAllQrCodes.php
✅ app/Console/Commands/GetBarangHash.php
✅ resources/views/fasilitas/barang/public-detail.blade.php
✅ database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php
✅ deploy.php (untuk automation)
```

### 🟡 FILE YANG DIMODIF (Upload/Replace)
```
✅ app/Models/Barang.php
✅ app/Http/Controllers/BarangController.php
✅ resources/views/fasilitas/barang/index.blade.php
✅ routes/web.php
```

### 📚 DOKUMENTASI (Optional, tapi recommended)
```
✅ START_HERE_QR_CODE.md
✅ QUICK_DEPLOYMENT_QR_CODE.md
✅ QR_CODE_READY_FOR_PRODUCTION.md
✅ DOKUMENTASI_QR_CODE_BARANG.md
✅ API_REFERENCE_QR_CODE_BARANG.md
```

---

## 📝 Langkah-Langkah Update

### Via FTP/SFTP:
1. Open FileZilla (atau WinSCP, Cyberduck, dll)
2. Connect ke hosting dengan FTP credentials
3. Navigate ke folder public_html atau root aplikasi
4. Upload semua file dari checklist di atas
5. **PENTING**: Pastikan folder `public/storage/qr_codes/` sudah dibuat dengan permissions 755

### Via cPanel:
1. Login cPanel → File Manager
2. Upload file-file via "Upload" button
3. Atau edit langsung via browser (jika file tidak terlalu besar)

### Via SSH (Jika ada akses SSH):
```bash
cd /home/username/public_html/bumisultanAPP

# Download/copy file-file yang baru
# Kemudian:
php artisan migrate
php artisan cache:clear
php artisan view:clear
```

---

## 🚀 Quick Deploy Automation

### Opsi A: Gunakan Deploy Script (Recommended)
1. Upload `deploy.php` ke root aplikasi
2. Akses: `http://domainanda.com/deploy.php?key=bumisultan_deploy_2025`
3. Script akan:
   - ✅ Verify semua file ada
   - ✅ Clear cache
   - ✅ Clear views
   - ✅ Check migrations
4. **PENTING**: Hapus `deploy.php` setelah selesai!

### Opsi B: Buat File untuk Manual Check
Setelah upload semua file, jalankan:
```bash
php artisan check:barang-qr
php artisan migrate --step=1  (untuk migration baru)
php artisan cache:clear
```

---

## ✅ Verification Checklist (Setelah Upload)

- [ ] Semua file sudah ter-upload
- [ ] Database migration sudah berjalan
- [ ] `public/storage/qr_codes/` folder ada & writable
- [ ] Akses `/fasilitas/asset` berjalan normal
- [ ] Ikon mata terlihat di barang list
- [ ] Click mata → buka halaman publik dengan QR
- [ ] QR codes sudah ter-generate
- [ ] Tidak ada error di `storage/logs/laravel.log`

---

## 📞 Troubleshooting Setelah Upload

### Error: "File not found" 
→ Check path relatif sudah benar

### Error: "Permission denied"
→ Set folder permissions: `chmod 755 public/storage/qr_codes`

### QR tidak muncul
→ Jalankan: `php artisan qrcode:generate-all`

### Cache masih lama/stale
→ Jalankan: `php artisan cache:clear && php artisan view:clear`

---

## 🔐 Security Notes

**GANTI SECRET KEY di deploy.php:**
```php
$SECRET_KEY = 'bumisultan_deploy_2025';  ← GANTI INI!
```

Ubah menjadi string random yang sulit ditebak, misal:
```php
$SECRET_KEY = 'x7k9P#mQ2wLzF8vN5bR@jD';
```

---

## 📋 File Summary

| Category | Count | Size |
|----------|-------|------|
| New Files | 8 | ~50 KB |
| Modified Files | 4 | ~30 KB |
| Documentation | 14 | ~150 KB |
| Total | 26 | ~230 KB |

---

## ⏱️ Estimated Time

- **Via FTP:** 5-10 minutes
- **Via cPanel:** 10-15 minutes  
- **Via SSH:** 5 minutes
- **Via Deploy Script:** 2 minutes (automated)

---

**Status:** Ready to deploy ✅
**Date:** 2025-12-12
**System:** Laravel 10.10+
