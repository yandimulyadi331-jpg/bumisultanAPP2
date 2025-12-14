# 🎬 STEP-BY-STEP GUIDE - RESET DATABASE DI HOSTING VIA PHPMYADMIN

## 📺 VIDEO TUTORIAL (Text Version)

### SCENARIO: Anda sudah upload kode, sekarang mau reset database di hosting

---

## 🎯 PART 1: AKSES PHPMYADMIN

### Step 1: Buka Browser
```
1. Buka Firefox / Chrome / Edge
2. Ketik URL PHPMyAdmin:
   https://domain-anda.com/phpmyadmin
   
   Contoh real:
   https://bumisultan.com/phpmyadmin
   atau
   https://bumisultan.id/cpanel/phpmyadmin
```

### Step 2: Login
```
1. Username: [database username dari hosting]
   Contoh: bumisultan_user
   
2. Password: [database password dari hosting]
   Contoh: xxxxxx
   
3. Click "Go" atau "Login"
```

**Hasil:** Melihat list database di sidebar kiri

---

## 🎯 PART 2: SELECT DATABASE

### Step 3: Pilih Database
```
1. Di sidebar kiri, cari nama database pinjaman
   Contoh: bumisultan_db
   
2. Click nama database tersebut
   (Background akan berubah)

3. Tab yang available:
   - Structure
   - SQL
   - Search
   - Export
   - Import
```

**Hasil:** Database sudah selected (lihat top: "Database: bumisultan_db")

---

## 🎯 PART 3: JALANKAN SQL QUERY

### Step 4: Buka Tab SQL
```
1. Click tab "SQL" di bagian top
   
2. Text area besar akan muncul (tempat query)
```

### Step 5: Paste Query
```
1. Copy query di bawah ini:

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

2. Paste ke text area SQL
3. Cursor ada di text area
4. Ctrl+A (select all)
5. Ctrl+V (paste)
```

### Step 6: Execute Query
```
1. Scroll ke bawah
2. Cari tombol besar "Go" (biasanya biru)
3. Click tombol "Go"
```

**Wait:** Jangan close browser, tunggu loading

---

## ✅ PART 4: VERIFY HASIL

### Step 7: Lihat Result
```
Setelah query selesai:

1. Cari section "Query results" atau "Affected rows"

2. Lihat hasil SELECT COUNT:
   - pinjaman_count: 0 ✓
   - cicilan_count: 0 ✓
   - history_count: 0 ✓

3. Jika semua 0 → SUCCESS!
   Jika ada error → Lihat error message
```

### Contoh Output Sukses:
```
Query executed successfully.

pinjaman_count: 0
cicilan_count: 0
history_count: 0
```

---

## 🔍 PART 5: MANUAL VERIFY (Optional tapi Recommended)

### Step 8: Browse Tabel Pinjaman
```
1. Di sidebar kiri, click "pinjaman"
2. Tab "Browse" akan auto-select
3. Lihat tabel: seharusnya kosong
4. Jika ada data → Ada yang salah
```

### Step 9: Check Auto-Increment
```
1. Tab "Structure"
2. Lihat kolom "id"
3. Lihat "Auto Increment" field
4. Harus isi dengan nilai 1 (atau kosong)
```

---

## 📊 TROUBLESHOOTING PHPMYADMIN

### Issue 1: "Access Denied"
```
❌ Error: Access denied for user 'xxx'@'xxx'

✅ Solution:
- Double check username & password
- Verify user punya privileges
- Contact hosting untuk reset password
- Coba IP domain berbeda
```

### Issue 2: "Foreign Key Constraint Failed"
```
❌ Error: Cannot truncate table 'pinjaman_cicilan'; 
          it is referenced by another table

✅ Solution:
Query saya sudah include:
SET FOREIGN_KEY_CHECKS=0;  ← INI YANG DISABLE FK

Jadi seharusnya tidak ada error ini.
Jika tetap error: restart MySQL (contact hosting)
```

### Issue 3: "Table is Locked"
```
❌ Error: Table xxx is locked

✅ Solution:
1. Close PHPMyAdmin tab
2. Wait 5 menit
3. Open PHPMyAdmin lagi
4. Retry query

atau contact hosting untuk unlock
```

### Issue 4: "Query Timeout"
```
❌ Error: Script timeout after 300 seconds

✅ Solution:
Jalankan query per bagian:

-- Jalankan ini dulu:
SET FOREIGN_KEY_CHECKS=0;

-- Tunggu selesai, tekan Go

-- Terus jalankan ini:
TRUNCATE TABLE pinjaman_history;

-- Dst...
```

---

## 🎬 VIDEO WORKFLOW (dalam screenshot)

### Screenshot 1: PHPMyAdmin Login
```
URL: https://domain-anda.com/phpmyadmin
┌─────────────────────────────────┐
│ PHPMyAdmin Login                 │
│                                  │
│ Username: [_______________]      │
│ Password: [_______________]      │
│                                  │
│             [Go]                 │
└─────────────────────────────────┘
```

### Screenshot 2: Database Selected
```
Left Sidebar:          Main Area:
┌─────────────────┐   ┌──────────────────────┐
│ Databases       │   │ Database: bumisultan │
│ • bumisultan_db │   │ Tables:              │
│                 │   │ - users              │
│ Tables:         │   │ - karyawan           │
│ • pinjaman ←   │   │ - pinjaman           │
│ • pinjaman_ci  │   │ - pinjaman_cicilan  │
│ • pinjaman_hi  │   │ - pinjaman_history  │
│ • karyawan      │   │                      │
└─────────────────┘   └──────────────────────┘
```

### Screenshot 3: SQL Query Tab
```
Tabs: [Structure] [SQL] [Search] [Export] [Import]
                   ↑
┌────────────────────────────────────────────┐
│ SQL query:                                  │
│                                             │
│ SET FOREIGN_KEY_CHECKS=0;                  │
│ TRUNCATE TABLE pinjaman_history;           │
│ TRUNCATE TABLE pinjaman_cicilan;           │
│ TRUNCATE TABLE pinjaman;                   │
│ ALTER TABLE pinjaman AUTO_INCREMENT = 1;   │
│ ...                                         │
│                                             │
├────────────────────────────────────────────┤
│ [Go] [Clear] [Parse] [History]             │
└────────────────────────────────────────────┘
```

### Screenshot 4: Success Result
```
Query executed successfully.

MySQL returned an empty result set (produced by query 1).
Rows affected: 0

MySQL returned an empty result set (produced by query 2).
Rows affected: 0

... (more results)

pinjaman_count
0

cicilan_count
0

history_count
0

✅ All 0 = Success!
```

---

## 💡 TIPS & TRICKS

### Tip 1: Save Query untuk Reuse
```
1. Di PHPMyAdmin SQL tab
2. Bawah ada "History" section
3. Query sudah auto-save
4. Click untuk reuse
```

### Tip 2: Export Backup Sebelum Reset
```
1. Select database
2. Tab "Export"
3. Format: SQL
4. File name: backup_[date].sql
5. Click "Go"
6. Download selesai
```

### Tip 3: Cepat Browse Data
```
1. Click tabel nama
2. Langsung ke "Browse" tab
3. Lihat data real-time
4. Edit/Delete bisa langsung
```

### Tip 4: Pretty Print Query
```
1. Query area → click icon format
2. Atau: Ctrl+B
3. Query jadi readable
```

---

## ⏱️ DURATION

| Task | Time |
|------|------|
| Login PHPMyAdmin | 30 sec |
| Select database | 15 sec |
| Paste query | 30 sec |
| Execute | 5-10 sec |
| Verify | 20 sec |
| **Total** | **~2-3 min** |

---

## 🎯 FINAL CHECKLIST

Setelah reset database:

- [ ] Login PHPMyAdmin → Success
- [ ] Database selected
- [ ] Query executed → No error
- [ ] Count verification → All 0
- [ ] Browse tabel → Empty
- [ ] Close PHPMyAdmin
- [ ] Clear cache aplikasi (`php artisan cache:clear`)
- [ ] Test aplikasi → Buka /pinjaman
- [ ] Create pinjaman test → Successful

---

## 🆘 EMERGENCY CONTACT

Jika ada masalah:

1. **Baca:** Error message di PHPMyAdmin
2. **Cek:** Backup file ada?
3. **Restore:** Via Import tab
4. **Contact:** Hosting support dengan error message

---

**Good luck! Database reset seharusnya lancar sekarang!** 🚀

Jika ada pertanyaan, refer ke `PANDUAN_IMPLEMENTASI_HOSTING_PHPMYADMIN.md` untuk detail lengkap.

