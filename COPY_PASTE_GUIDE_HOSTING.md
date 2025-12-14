# 📋 COPY-PASTE GUIDE - IMPLEMENTASI DI HOSTING

User: "Saya mau langsung praktik, copy-paste aja"

**OK! Berikut command & query yang bisa langsung copy-paste!**

---

## 🎯 BAGIAN 1: SQL QUERY UNTUK PHPMYADMIN

### ⚠️ BACKUP DULU!

**Copy query ini ke PHPMyAdmin untuk BACKUP:**

```sql
-- BACKUP sebelum reset
CREATE TABLE pinjaman_backup_20251212 AS SELECT * FROM pinjaman;
CREATE TABLE pinjaman_cicilan_backup_20251212 AS SELECT * FROM pinjaman_cicilan;
CREATE TABLE pinjaman_history_backup_20251212 AS SELECT * FROM pinjaman_history;
```

Paste di: **PHPMyAdmin → SQL tab → Click Go**

---

### ✅ RESET QUERY - COPY INI

**Copy seluruh query ini:**

```sql
SET FOREIGN_KEY_CHECKS=0;

TRUNCATE TABLE pinjaman_history;
TRUNCATE TABLE pinjaman_cicilan;
TRUNCATE TABLE pinjaman;

ALTER TABLE pinjaman AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_history AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS=1;

SELECT COUNT(*) as pinjaman_count FROM pinjaman;
SELECT COUNT(*) as cicilan_count FROM pinjaman_cicilan;
SELECT COUNT(*) as history_count FROM pinjaman_history;
```

**Langkah:**
1. Copy seluruh query di atas
2. Buka PHPMyAdmin
3. Click database pinjaman
4. Tab "SQL"
5. Paste query
6. Click tombol "Go"
7. Tunggu... selesai!

---

### ✅ VERIFY QUERY - COPY INI

**Jika ingin verify setelah reset:**

```sql
SELECT 
    (SELECT COUNT(*) FROM pinjaman) as pinjaman_total,
    (SELECT COUNT(*) FROM pinjaman_cicilan) as cicilan_total,
    (SELECT COUNT(*) FROM pinjaman_history) as history_total;
```

Expected result:
```
pinjaman_total: 0
cicilan_total: 0
history_total: 0
```

---

### 📊 JIKA PERLU RESTORE (Gapcok)

**Jika ada yang salah, restore dengan query ini:**

```sql
-- RESTORE dari backup
TRUNCATE TABLE pinjaman;
TRUNCATE TABLE pinjaman_cicilan;
TRUNCATE TABLE pinjaman_history;

INSERT INTO pinjaman SELECT * FROM pinjaman_backup_20251212;
INSERT INTO pinjaman_cicilan SELECT * FROM pinjaman_cicilan_backup_20251212;
INSERT INTO pinjaman_history SELECT * FROM pinjaman_history_backup_20251212;

-- Verify restore
SELECT COUNT(*) FROM pinjaman;
SELECT COUNT(*) FROM pinjaman_cicilan;
SELECT COUNT(*) FROM pinjaman_history;
```

---

## 🎯 BAGIAN 2: FILE MANA YANG HARUS DIUPLOAD

### File 1:
```
Local:  d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\PinjamanController.php
Hosting: /app/Http/Controllers/PinjamanController.php
```

### File 2:
```
Local:  d:\bumisultanAPP\bumisultanAPP\routes\web.php
Hosting: /routes/web.php
```

### File 3:
```
Local:  d:\bumisultanAPP\bumisultanAPP\resources\views\pinjaman\show.blade.php
Hosting: /resources/views/pinjaman/show.blade.php
```

### File 4:
```
Local:  d:\bumisultanAPP\bumisultanAPP\reset_pinjaman_quick.php
Hosting: /reset_pinjaman_quick.php  (di root)
```

---

## 🎯 BAGIAN 3: CACHE CLEAR

### Via SSH (Terminal):
```bash
cd /home/username/public_html/bumisultan
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Via PHP File (Jika tidak ada SSH):

**1. Buat file `clear_cache.php` di root:**

```php
<?php
// Clear Laravel cache
system('php artisan cache:clear');
system('php artisan view:clear');
system('php artisan config:clear');
echo "Cache cleared!";
?>
```

**2. Upload ke: `/public/clear_cache.php`**

**3. Akses di browser:**
```
https://domain-anda.com/clear_cache.php
```

**4. Hasil: "Cache cleared!" message**

**5. DELETE file setelah selesai!**

---

## 🎯 BAGIAN 4: TEST QUERIES

### Test 1: Check Pinjaman Kosong
```sql
SELECT * FROM pinjaman LIMIT 10;
-- Harus return: Empty result set
```

### Test 2: Check Auto-Increment Reset
```sql
SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'bumisultan_db' AND TABLE_NAME = 'pinjaman';
-- Harus return: 1 atau kosong
```

### Test 3: Check Foreign Key
```sql
SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'pinjaman_cicilan' 
AND COLUMN_NAME = 'pinjaman_id';
-- Harus return: pinjaman_cicilan_pinjaman_id_foreign
```

---

## 🎯 BAGIAN 5: DIAGNOSTIC QUERIES

### Lihat Semua Pinjaman (Jika ada):
```sql
SELECT id, nomor_pinjaman, kategori_peminjam, karyawan_id, nama_peminjam_lengkap, status
FROM pinjaman
ORDER BY id DESC
LIMIT 20;
```

### Lihat Pinjaman dari Karyawan Tertentu:
```sql
SELECT * FROM pinjaman WHERE karyawan_id = '12345';
-- Ganti 12345 dengan NIK
```

### Lihat Pinjaman yang Status Belum Disetujui:
```sql
SELECT * FROM pinjaman WHERE status IN ('pengajuan', 'review');
```

### Lihat Cicilan yang Terlambat:
```sql
SELECT * FROM pinjaman_cicilan WHERE status = 'terlambat';
```

---

## 📞 QUICK REFERENCE - MYSQL BASIC

### Show Databases:
```sql
SHOW DATABASES;
```

### Show Tables:
```sql
SHOW TABLES;
```

### Describe Table:
```sql
DESCRIBE pinjaman;
```

### Count Records:
```sql
SELECT COUNT(*) FROM pinjaman;
```

### Check Table Size:
```sql
SELECT 
  TABLE_NAME,
  ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'bumisultan_db'
ORDER BY (data_length + index_length) DESC;
```

---

## 🔧 TROUBLESHOOTING QUERIES

### Jika Foreign Key Error:

**Disable FK:**
```sql
SET FOREIGN_KEY_CHECKS=0;
```

**Enable FK:**
```sql
SET FOREIGN_KEY_CHECKS=1;
```

### Jika Table Locked:

```sql
UNLOCK TABLES;
SHOW PROCESSLIST;
-- Ganti ID dari processlist
KILL 12345;
```

### Jika Need Full Database Repair:

```sql
REPAIR TABLE pinjaman;
REPAIR TABLE pinjaman_cicilan;
REPAIR TABLE pinjaman_history;
```

---

## 📊 PHPMYADMIN CHEAT SHEET

| Aksi | SQL |
|------|-----|
| Backup table | `CREATE TABLE tbl_backup AS SELECT * FROM tbl;` |
| Delete all | `TRUNCATE TABLE tbl;` |
| Reset auto-inc | `ALTER TABLE tbl AUTO_INCREMENT = 1;` |
| Count rows | `SELECT COUNT(*) FROM tbl;` |
| Delete by condition | `DELETE FROM tbl WHERE status = 'old';` |
| Update column | `UPDATE tbl SET col='value' WHERE id=1;` |
| Show structure | `DESCRIBE tbl;` |
| Drop table | `DROP TABLE tbl;` |

---

## 🎯 STEP-BY-STEP EXECUTION ORDER

**Eksekusi dalam urutan ini:**

### Step 1: Backup
```sql
CREATE TABLE pinjaman_backup_20251212 AS SELECT * FROM pinjaman;
CREATE TABLE pinjaman_cicilan_backup_20251212 AS SELECT * FROM pinjaman_cicilan;
CREATE TABLE pinjaman_history_backup_20251212 AS SELECT * FROM pinjaman_history;
```
➜ Click "Go"

### Step 2: Reset
```sql
SET FOREIGN_KEY_CHECKS=0;

TRUNCATE TABLE pinjaman_history;
TRUNCATE TABLE pinjaman_cicilan;
TRUNCATE TABLE pinjaman;

ALTER TABLE pinjaman AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_history AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS=1;
```
➜ Click "Go"

### Step 3: Verify
```sql
SELECT COUNT(*) FROM pinjaman;
SELECT COUNT(*) FROM pinjaman_cicilan;
SELECT COUNT(*) FROM pinjaman_history;
```
➜ Click "Go" ➜ Result: All 0 ✓

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
```

### Step 5: Test
```
Browser: https://domain-anda.com/pinjaman
Expected: Empty list, no error
```

---

## 📞 FILE REFERENCE

| File | Untuk Apa |
|------|-----------|
| `PinjamanController.php` | Logic orphan handling |
| `web.php` | Routes untuk orphan management |
| `show.blade.php` | UI alert & buttons |
| `reset_pinjaman_quick.php` | Local reset (jika butuh) |

---

## ✅ FINAL CHECKLIST

```
BEFORE:
☐ Backup database sudah dibuat
☐ Download backup file ke lokal

EXECUTE:
☐ Upload 4 file ke hosting
☐ Copy-paste backup query → Execute
☐ Copy-paste reset query → Execute
☐ Copy-paste verify query → Execute (all 0?)
☐ Clear cache via SSH/PHP

AFTER:
☐ Open /pinjaman di browser
☐ Create pinjaman test
☐ Approve & cairkan
☐ Bayar cicilan
☐ All good!
```

---

## 🎉 DONE!

Semua copy-paste ready. Tinggal:
1. Backup
2. Execute query
3. Verify
4. Done!

Good luck! 🚀

Jika ada error, coba troubleshooting query di atas.

