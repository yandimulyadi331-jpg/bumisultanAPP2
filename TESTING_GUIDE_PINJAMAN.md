# TESTING GUIDE - PINJAMAN ORPHAN MANAGEMENT

## 🎯 OBJECTIVE
Memastikan semua fitur baru bekerja dengan baik sebelum production deployment

## 🧪 TEST SCENARIOS

### TEST 1: Reset Data Pinjaman
**Tujuan:** Verifikasi script reset berfungsi

**Steps:**
```bash
cd d:\bumisultanAPP\bumisultanAPP
php reset_pinjaman_quick.php
```

**Expected Output:**
```
=== RESET DATA PINJAMAN (QUICK MODE) ===
Waktu: 12-12-2025 18:46:57

1. Data sebelum reset:
   - Pinjaman: [number]
   - Cicilan: [number]
   - History: [number]

2. Membuat backup...
   ✓ Backup: backup-pinjaman/reset_backup_2025-12-12_18-46-57.json

3. Menghapus data...
   ✓ History dihapus
   ✓ Cicilan dihapus
   ✓ Pinjaman dihapus

4. Reset auto increment...
   ✓ Auto increment reset

5. Verifikasi:
   - Pinjaman: 0 ✓
   - Cicilan: 0 ✓
   - History: 0 ✓

✓ RESET SELESAI!
```

**Pass Criteria:**
- ✓ No error messages
- ✓ Pinjaman count = 0
- ✓ Backup file created

---

### TEST 2: View List Pinjaman
**Tujuan:** Pastikan list view tidak error

**Steps:**
1. Open browser → `http://127.0.0.1:8000/pinjaman`
2. Lihat list pinjaman (seharusnya kosong setelah reset)

**Expected:**
- Page load < 1 second
- No error messages
- Filter dropdown works (kategori, status, bulan, tahun)
- Search box works

**Pass Criteria:**
- ✓ Page loads without error
- ✓ Empty list (after reset)
- ✓ All filters functional

---

### TEST 3: Create Pinjaman
**Tujuan:** Test create flow

**Steps:**
1. Click "Buat Pinjaman Baru"
2. Select kategori: **CREW**
3. Select karyawan: **Pick any active karyawan**
4. Fill tanggal pengajuan: **Today**
5. Fill jumlah pengajuan: **Rp 5.000.000**
6. Fill tenor: **12** bulan
7. Fill tanggal jatuh tempo: **1** (tanggal 1 setiap bulan)
8. Fill tujuan: **Test Pinjaman**
9. Click "Simpan"

**Expected:**
- ✓ Redirect to detail page
- ✓ "Pengajuan pinjaman berhasil dibuat"
- ✓ Status = "PENGAJUAN"

---

### TEST 4: View Detail Pinjaman (Normal - Ada Karyawan)
**Tujuan:** Test detail view dengan karyawan existing

**Steps:**
1. Click pinjaman yang baru dibuat (dari TEST 3)
2. Lihat semua section

**Expected:**
- ✓ No error tampil
- ✓ Semua info karyawan tampil
- ✓ Informasi pinjaman lengkap
- ✓ Tab cicilan kosong (belum approved)
- ✓ Tombol action (Review, Approve, Reject)

---

### TEST 5: Approve Pinjaman
**Tujuan:** Test approval workflow

**Steps:**
1. Di detail pinjaman (dari TEST 4)
2. Click "Setujui Pinjaman"
3. Fill jumlah disetujui: **5000000**
4. Click "Setujui"

**Expected:**
- ✓ Status berubah ke "DISETUJUI"
- ✓ "Pinjaman berhasil disetujui" message
- ✓ Tombol "Cairkan Dana" muncul

---

### TEST 6: Cairkan Pinjaman
**Tujuan:** Test pencairan

**Steps:**
1. Di detail (status DISETUJUI)
2. Click "Cairkan Dana"
3. Pilih metode: **Tunai**
4. Click "Cairkan"

**Expected:**
- ✓ Status → "DICAIRKAN"
- ✓ Jadwal cicilan otomatis dibuat (12 cicilan)
- ✓ "Pinjaman berhasil dicairkan" message
- ✓ Tab cicilan sekarang ada data

---

### TEST 7: Bayar Cicilan
**Tujuan:** Test pembayaran cicilan

**Steps:**
1. Di detail pinjaman (sudah dicairkan)
2. Di tab cicilan, click "Bayar" cicilan ke-1
3. Modal bayar muncul
4. Jumlah bayar: auto-filled dengan jumlah cicilan
5. Metode: **Tunai**
6. Click "Proses Pembayaran"

**Expected:**
- ✓ Modal tutup
- ✓ Cicilan status → "LUNAS"
- ✓ Cicilan row highlight green
- ✓ "Pembayaran cicilan berhasil" message

---

### TEST 8: Orphan Detection (Manual Test)
**Tujuan:** Test handling karyawan yang sudah dihapus

**Prerequisites:**
- Pinjaman dengan status ada (dari TEST 7)
- Akses database langsung atau via tinker

**Steps:**
```php
// Via php artisan tinker
php artisan tinker

// Delete karyawan yang punya pinjaman
$karyawan = App\Models\Karyawan::first();
$karyawan->delete();  // atau forceDelete()

// Exit tinker
exit
```

**Then:**
1. Refresh detail pinjaman
2. Cek apakah alert muncul

**Expected:**
- ✓ Alert orange: "Data Karyawan Tidak Ditemukan"
- ✓ Alert menjelaskan NIK tidak tersedia
- ✓ Alert mention: "Anda masih bisa bayar/tunda/hapus"
- ✓ Tombol "Ubah Keterangan" muncul
- ✓ Tombol "Hapus Pinjaman" muncul

---

### TEST 9: Update Orphan Pinjaman
**Tujuan:** Test edit data orphan

**Prerequisites:**
- Dari TEST 8 (ada orphan pinjaman)

**Steps:**
1. Click "Ubah Keterangan"
2. Modal terbuka
3. Change "Nama Peminjam Lengkap": **"Ahmad Resign"**
4. Add keterangan: **"Karyawan sudah resign, cicilan masih berjalan"**
5. Click "Simpan Perubahan"

**Expected:**
- ✓ Modal tutup
- ✓ "Data pinjaman orphan berhasil diperbarui!" message
- ✓ Nama peminjam berubah di display
- ✓ Keterangan terupdate

---

### TEST 10: Force Delete Orphan Pinjaman
**Tujuan:** Test penghapusan pinjaman orphan

**Prerequisites:**
- Dari TEST 9 (orphan pinjaman yang sudah diupdate)

**Steps:**
1. Click "Hapus Pinjaman"
2. Button open confirmation dialog
3. Click "OK" di dialog
4. Wait for process

**Expected:**
- ✓ Redirect to `/pinjaman` list
- ✓ "Pinjaman orphan berhasil dihapus!" message
- ✓ Pinjaman tidak ada di list lagi
- ✓ Database: pinjaman, cicilan, history semuanya gone

**Verify:**
```php
php artisan tinker

// Check pinjaman deleted
App\Models\Pinjaman::count()  // Should be 0 or less

// Check cicilan deleted
App\Models\PinjamanCicilan::count()  // Should be 0
```

---

### TEST 11: Backup File Integrity
**Tujuan:** Verifikasi backup bisa dibuka

**Steps:**
1. Find backup file: `storage/app/backup-pinjaman/reset_backup_*.json`
2. Open dengan text editor (VS Code, Notepad++)
3. Check struktur

**Expected:**
- ✓ JSON format valid
- ✓ Contains: backup_time, pinjaman_count, cicilan_count, history_count
- ✓ Contains: data > pinjaman array
- ✓ File tidak corrupt

---

### TEST 12: Null Handling - Multiple Access
**Tujuan:** Test bahwa view tidak error di berbagai kondisi

**Steps:**

**Case A: View Cicilan dengan Orphan**
1. Create pinjaman cicilan (already done in TEST 6-7)
2. Delete karyawan
3. Access `/pinjaman/[id]` → Tab cicilan

**Case B: Edit Orphan (Should Fail Gracefully)**
1. Try access edit form untuk orphan pinjaman
2. Should see error: "Pinjaman tidak dapat diubah karena sudah diproses"

**Case C: Search Orphan**
1. Buat beberapa pinjaman
2. Delete beberapa karyawan
3. Use search di `/pinjaman`
4. Should find orphan pinjaman by nomor/nama

**Expected:**
- ✓ No null pointer exception
- ✓ Graceful error messages
- ✓ All operations work as documented

---

## 📊 TEST RESULT SUMMARY

| Test # | Test Name | Status | Notes |
|--------|-----------|--------|-------|
| 1 | Reset Data | ⬜ | |
| 2 | View List | ⬜ | |
| 3 | Create Pinjaman | ⬜ | |
| 4 | View Detail (Normal) | ⬜ | |
| 5 | Approve | ⬜ | |
| 6 | Cairkan | ⬜ | |
| 7 | Bayar Cicilan | ⬜ | |
| 8 | Orphan Detection | ⬜ | |
| 9 | Update Orphan | ⬜ | |
| 10 | Force Delete Orphan | ⬜ | |
| 11 | Backup Integrity | ⬜ | |
| 12 | Null Handling | ⬜ | |

**Legend:**
- ⬜ To Do
- 🟨 In Progress
- ✅ Pass
- ❌ Fail

---

## 🔧 TROUBLESHOOTING

### Issue: "View not updated"
**Solution:**
- Clear cache: `php artisan view:clear`
- Refresh browser: `Ctrl+Shift+R`

### Issue: "Script not found"
**Solution:**
- Check file exists: `ls reset_pinjaman_quick.php`
- Check composer autoload: `composer dump-autoload`

### Issue: "Button not showing"
**Solution:**
- Check orphan condition: karyawan_id not null but karyawan null
- Manually test in tinker:
```php
$p = App\Models\Pinjaman::find(1);
$p->kategori_peminjam == 'crew'  // true
!$p->karyawan  // true = orphan
```

### Issue: "Delete failed"
**Solution:**
- Check foreign keys: `SHOW CREATE TABLE pinjaman_cicilan`
- Disable FK: `SET FOREIGN_KEY_CHECKS=0`

---

## ✅ FINAL CHECKLIST

Before deploy to production:

- [ ] All 12 tests passed
- [ ] No console errors (F12 DevTools)
- [ ] Database logs clean
- [ ] Backup files created
- [ ] Performance tested (< 1s load)
- [ ] Mobile responsive tested
- [ ] Keyboard navigation works
- [ ] Accessibility checked

---

**Test Date:** 12 December 2024
**Tester:** [Your Name]
**Status:** Ready for Deployment ✅

