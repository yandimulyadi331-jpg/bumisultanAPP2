# PANDUAN IMPLEMENTASI DI HOSTING - PINJAMAN ERROR FIX

## 🎯 OVERVIEW

Panduan ini untuk mengaplikasikan solusi pinjaman error ke aplikasi yang sudah live di hosting dengan cara:
1. Update kode via FTP/Git
2. Reset database via PHPMyAdmin
3. Manage orphan pinjaman

---

## 📋 STEP 1: UPLOAD KODE KE HOSTING

### Opsi A: Via FTP (Recommended untuk quick fix)

**Software yang dibutuhkan:**
- FileZilla (gratis: https://filezilla-project.org/)
- atau WinSCP

**Langkah:**

1. **Buka FileZilla**
2. **Connect ke server:**
   - Host: `[domain-anda.com]` atau IP server
   - Username: `[ftp-username]`
   - Password: `[ftp-password]`
   - Port: 21 (atau yang disediakan hosting)

3. **Navigate ke folder aplikasi:**
   ```
   /public_html/bumisultan/
   atau
   /home/[username]/public_html/
   ```

4. **Upload file-file yang berubah:**
   ```
   ✓ app/Http/Controllers/PinjamanController.php
   ✓ routes/web.php
   ✓ resources/views/pinjaman/show.blade.php
   ✓ reset_pinjaman_quick.php (ke root folder)
   ```

**Drag & drop file dari local ke remote:**
- Local: `d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\PinjamanController.php`
- Remote: `/app/Http/Controllers/PinjamanController.php`

### Opsi B: Via Git (Lebih profesional)

```bash
# Di server hosting via SSH/Terminal
cd /home/[username]/public_html/bumisultan

# Pull changes dari git
git pull origin main

# Atau jika pakai staging branch
git fetch origin
git checkout feature/pinjaman-orphan-fix
git merge origin/feature/pinjaman-orphan-fix
```

### Opsi C: Via File Manager di cPanel/Plesk

1. Login ke cPanel/Plesk hosting
2. Buka "File Manager"
3. Navigate ke public_html
4. Upload files via "Upload" button
5. Extract jika ada ZIP

---

## 📊 STEP 2: RESET DATABASE VIA PHPMYADMIN

### Cara Akses PHPMyAdmin

**URL:**
```
http://[domain-anda.com]/phpmyadmin
atau
https://[hosting-control-panel].com/phpmyadmin
```

**Login:**
- Username: `[database-username]`
- Password: `[database-password]`

### Method 1: Manual Query (Recommended - Paling Aman)

**Step-by-step:**

1. **Login PHPMyAdmin**

2. **Select Database:**
   - Left sidebar → Click database name (e.g., `bumisultan_db`)

3. **Open SQL Tab:**
   - Top menu → Click "SQL" tab

4. **Paste Query:**
```sql
-- STEP 1: Disable Foreign Key Checks
SET FOREIGN_KEY_CHECKS=0;

-- STEP 2: Backup data (optional - create table)
CREATE TABLE pinjaman_backup AS SELECT * FROM pinjaman;
CREATE TABLE pinjaman_cicilan_backup AS SELECT * FROM pinjaman_cicilan;
CREATE TABLE pinjaman_history_backup AS SELECT * FROM pinjaman_history;

-- STEP 3: Delete data
TRUNCATE TABLE pinjaman_history;
TRUNCATE TABLE pinjaman_cicilan;
TRUNCATE TABLE pinjaman;

-- STEP 4: Reset Auto Increment
ALTER TABLE pinjaman AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1;
ALTER TABLE pinjaman_history AUTO_INCREMENT = 1;

-- STEP 5: Enable Foreign Key Checks
SET FOREIGN_KEY_CHECKS=1;

-- Verification
SELECT COUNT(*) as pinjaman_count FROM pinjaman;
SELECT COUNT(*) as cicilan_count FROM pinjaman_cicilan;
SELECT COUNT(*) as history_count FROM pinjaman_history;
```

5. **Click "Go"** (tombol di bawah area query)

6. **Result:**
   - Jika sukses: Akan ada message "Query executed successfully"
   - Verify: Count semuanya harus 0

### Method 2: Via SQL File (Jika Query Terlalu Panjang)

1. **Buat file SQL:**
   ```
   reset_pinjaman.sql
   ```

2. **Copy paste query di atas ke file**

3. **Upload ke PHPMyAdmin:**
   - PHPMyAdmin → SQL tab
   - Bottom: "Or browse the server" → Select `reset_pinjaman.sql`
   - Click "Go"

### Method 3: Via Table Management (Manual Click)

**Hanya jika query tidak bisa:**

1. **Left sidebar → Click table `pinjaman_history`**
2. **Top menu → "Operations" tab → "Empty the table"**
3. **Confirm**
4. **Repeat untuk `pinjaman_cicilan`**
5. **Repeat untuk `pinjaman`**
6. **Then manually reset auto-increment:**
   - Click tabel
   - Tab "Structure"
   - Cari kolom "id"
   - Edit → Change "Auto Increment" ke 1

---

## 🔍 STEP 3: VERIFY RESET BERHASIL

### Via PHPMyAdmin:

1. **Select tabel `pinjaman`**
2. **Tab "Browse"**
3. **Lihat isi tabel:**
   - Jika kosong → ✅ Sukses
   - Jika masih ada data → ❌ Ada yang salah

**Atau via Query:**
```sql
SELECT COUNT(*) as total FROM pinjaman;
SELECT COUNT(*) as total FROM pinjaman_cicilan;
SELECT COUNT(*) as total FROM pinjaman_history;
```

Semua harus return `0`

### Via Aplikasi Web:

1. **Buka browser → Akses aplikasi hosting**
   ```
   https://domain-anda.com/pinjaman
   ```

2. **Lihat list pinjaman:**
   - Should be empty
   - No error message

3. **Test create pinjaman baru:**
   - Click "Buat Pinjaman"
   - Fill form
   - Save
   - Success → ✅ Database OK

---

## 🧪 STEP 4: TESTING DI HOSTING

### Test 1: Akses List Pinjaman
```
URL: https://domain-anda.com/pinjaman
Expected: Empty list, no error
```

### Test 2: Create Pinjaman Baru
```
1. Click "Buat Pinjaman Baru"
2. Select karyawan: Pilih salah satu
3. Isi form pinjaman
4. Click "Simpan"
Expected: Redirect ke detail, status "PENGAJUAN"
```

### Test 3: Approve & Cairkan
```
1. Di detail pinjaman
2. Click "Setujui Pinjaman"
3. Isi jumlah disetujui
4. Click "Setujui"
5. Click "Cairkan Dana"
Expected: Status "DICAIRKAN", cicilan terbuat
```

### Test 4: Bayar Cicilan
```
1. Tab "Jadwal Cicilan"
2. Click "Bayar" cicilan pertama
3. Isi jumlah bayar
4. Click "Proses Pembayaran"
Expected: Cicilan status "LUNAS"
```

### Test 5: Test Orphan Detection (Opsional)

**Via PHPMyAdmin:**
```sql
-- Simulasi: hapus karyawan dari pinjaman
DELETE FROM karyawan WHERE nik = '12345';
-- (ganti 12345 dengan NIK karyawan yang ada pinjaman)
```

**Atau manual di PHPMyAdmin:**
1. Click tabel `karyawan`
2. Find karyawan yang ada pinjaman
3. Click "Delete" (icon sampah)
4. Confirm

**Kemudian:**
1. Akses pinjaman yang tadi dari karyawan itu
2. Lihat alert warning: "Data Karyawan Tidak Ditemukan"
3. Tombol "Ubah Keterangan" & "Hapus Pinjaman" muncul

---

## 📋 STEP 5: CLEAR CACHE & RESTART

**Setelah upload, jalankan ini:**

### Via SSH/Terminal:
```bash
# SSH ke server
ssh user@domain.com

# Go to app folder
cd /home/[user]/public_html/bumisultan

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Optimize
php artisan optimize
```

### Jika Tidak Ada SSH Access:

**Via PHP File (Create & Run):**

1. **Buat file `clear_cache.php` di root:**
```php
<?php
// Clear cache via PHP
system('php artisan cache:clear');
system('php artisan view:clear');
system('php artisan config:clear');
echo "Cache cleared!";
?>
```

2. **Upload ke `/public/` folder**

3. **Access via browser:**
   ```
   https://domain-anda.com/clear_cache.php
   ```

4. **Delete file setelah selesai**

---

## 🔒 STEP 6: BACKUP DATABASE (PENTING!)

### Sebelum Melakukan Reset:

**Via PHPMyAdmin:**

1. **Select database**
2. **Top menu → "Export"**
3. **Format: SQL**
4. **Click "Go"**
5. **File akan download**
6. **Save dengan nama:** `backup_pinjaman_[tanggal].sql`

**Contoh:**
```
backup_pinjaman_2025-12-12.sql
```

### Jika Perlu Restore:

1. **PHPMyAdmin → Import tab**
2. **Click "Choose File"**
3. **Select backup SQL file**
4. **Click "Go"**
5. **Done - data restore**

---

## 📊 PHPMYADMIN CHEAT SHEET

### Common Tasks:

**1. Lihat jumlah data di tabel:**
```sql
SELECT COUNT(*) FROM pinjaman;
SELECT COUNT(*) FROM pinjaman_cicilan;
```

**2. Lihat recent data:**
```sql
SELECT * FROM pinjaman ORDER BY id DESC LIMIT 10;
```

**3. Delete semua data tabel:**
```sql
TRUNCATE TABLE pinjaman;
```

**4. Reset auto-increment:**
```sql
ALTER TABLE pinjaman AUTO_INCREMENT = 1;
```

**5. Cari data spesifik:**
```sql
SELECT * FROM pinjaman WHERE kategori_peminjam = 'crew';
```

**6. Update data:**
```sql
UPDATE pinjaman SET status = 'dibatalkan' WHERE id = 1;
```

**7. Delete data spesifik:**
```sql
DELETE FROM pinjaman WHERE status = 'pengajuan' AND created_at < '2025-01-01';
```

---

## ❌ TROUBLESHOOTING

### Problem 1: "Access Denied" di PHPMyAdmin
**Solution:**
- Verify username/password benar
- Contact hosting provider untuk credentials
- Cek database user has required permissions

### Problem 2: "Foreign Key Constraint Error"
**Solution:**
```sql
SET FOREIGN_KEY_CHECKS=0;
-- ... run delete queries
SET FOREIGN_KEY_CHECKS=1;
```

### Problem 3: Query Timeout
**Solution:**
- Split query menjadi lebih kecil
- Jalankan per tabel
- Contact hosting jika issue persist

### Problem 4: File Upload Failed (FTP)
**Solution:**
- Check file size < hosting limit
- Verify FTP permissions (755)
- Try text mode transfer
- Use SFTP instead of FTP

### Problem 5: Error "Disk quota exceeded"
**Solution:**
- Delete old logs: `storage/logs/`
- Delete old backups: `storage/backup-pinjaman/`
- Contact hosting untuk increase disk

---

## 📈 MONITORING SETELAH DEPLOYMENT

### Daily:
- ✓ Cek `/pinjaman` - jalan normal?
- ✓ Create pinjaman test - OK?
- ✓ Check error logs

### Weekly:
- ✓ Backup database
- ✓ Monitor disk usage
- ✓ Check performance

### Monthly:
- ✓ Full backup
- ✓ Performance audit
- ✓ Security check

---

## 🚀 QUICK DEPLOYMENT CHECKLIST

Sebelum publish ke hosting:

- [ ] Backup database lama
- [ ] Upload file-file yang berubah via FTP
- [ ] Clear cache (php artisan cache:clear)
- [ ] Test: akses /pinjaman - no error
- [ ] Test: create pinjaman baru - OK
- [ ] Test: approve & cairkan - OK
- [ ] Test: bayar cicilan - OK
- [ ] Monitor untuk 24 jam
- [ ] Archive backup file

---

## 📞 REFERENCE COMMANDS

### PHPMyAdmin - Useful SQL:

**Backup sebelum reset:**
```sql
CREATE TABLE pinjaman_backup_[date] AS SELECT * FROM pinjaman;
```

**Verify setelah reset:**
```sql
SELECT 
    COUNT(*) as pinjaman_count,
    (SELECT COUNT(*) FROM pinjaman_cicilan) as cicilan_count,
    (SELECT COUNT(*) FROM pinjaman_history) as history_count;
```

**List orphan pinjaman (after test):**
```sql
SELECT p.id, p.nomor_pinjaman, p.karyawan_id, p.nama_peminjam_lengkap
FROM pinjaman p
WHERE p.kategori_peminjam = 'crew' 
AND p.karyawan_id NOT IN (SELECT nik FROM karyawan);
```

---

## 🎯 SUMMARY

| Step | Action | Tool | Time |
|------|--------|------|------|
| 1 | Upload kode | FTP/Git | 5 min |
| 2 | Reset DB | PHPMyAdmin | 2 min |
| 3 | Verify | PHPMyAdmin | 1 min |
| 4 | Test app | Browser | 10 min |
| 5 | Clear cache | SSH/PHP | 1 min |
| 6 | Monitor | Browser | 5 min |
| **Total** | | | **~24 min** |

---

**Important Notes:**
- Backup ALWAYS sebelum reset
- Test di staging dulu jika ada
- Monitor untuk 24 jam setelah deploy
- Keep backup files untuk at least 30 hari

**Success = Semua test PASS + No error logs + Users bisa create/bayar pinjaman** ✅

