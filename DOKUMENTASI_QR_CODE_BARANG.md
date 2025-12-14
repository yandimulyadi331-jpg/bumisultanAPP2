# 📱 FITUR QR CODE BARANG - DOKUMENTASI LENGKAP

## 🎯 Ringkasan Fitur

Fitur QR Code Barang memungkinkan setiap barang di sistem Fasilitas Asset untuk memiliki **QR Code unik** yang dapat di-scan oleh siapa saja **tanpa perlu login**. Ketika QR Code di-scan, halaman detail barang langsung terbuka menampilkan informasi lengkap dalam format mobile-friendly.

### ✨ Fitur Utama:
- ✅ **Generate QR Code Otomatis** - QR Code dibuat otomatis saat barang ditambah/diupdate
- ✅ **Akses Tanpa Login** - Halaman detail dapat diakses siapa saja
- ✅ **Mobile-Friendly** - Interface responsif untuk akses dari HP
- ✅ **Download QR Code** - User dapat mengunduh QR Code sebagai file PNG
- ✅ **API Endpoint** - Tersedia JSON API untuk integrasi pihak ketiga
- ✅ **Unique Hash** - Setiap barang punya hash unik untuk keamanan

---

## 🔧 Implementasi Teknis

### 1. Database Migration
**File:** `database/migrations/2025_12_12_000001_add_qr_code_to_barangs_table.php`

Menambahkan kolom-kolom berikut ke tabel `barangs`:
```sql
- qr_code_data (text) - Data URL untuk QR Code PNG
- qr_code_hash (string, unique) - Hash unik untuk URL publik
- qr_code_path (string) - Path file QR Code PNG
- status_barang (enum) - Status barang (Aktif/Rusak Total/Hilang)
```

### 2. Model Update
**File:** `app/Models/Barang.php`

Menambahkan kolom ke `$fillable`:
```php
'qr_code_data',
'qr_code_hash',
'qr_code_path',
'status_barang'
```

### 3. Service untuk Generate QR Code
**File:** `app/Services/QrCodeBarangService.php`

Service ini menyediakan methods untuk:
- `generateQrCode($barang)` - Generate QR Code baru
- `regenerateQrCode($barang)` - Regenerate QR Code
- `deleteQrCode($barang)` - Hapus file QR Code
- `getQrCodeUrl($barang)` - Dapatkan URL QR Code
- `getQrCodeHtml($barang, $alt, $attributes)` - Generate HTML img tag

### 4. Controller Publik
**File:** `app/Http/Controllers/BarangPublicController.php`

Methods:
- `publicDetail($hash)` - Tampilkan halaman detail barang publik
- `downloadQrCode($hash)` - Download QR Code sebagai file PNG
- `getBarangDetails($hash)` - API endpoint untuk JSON response

### 5. Routes Publik
**File:** `routes/web.php`

```php
// Barang QR Code Public Routes (No Login Required)
Route::controller(\App\Http\Controllers\BarangPublicController::class)->group(function () {
    Route::get('/barang/qr/{hash}', 'publicDetail')->name('barang.public-detail');
    Route::get('/barang/qr/{hash}/download', 'downloadQrCode')->name('barang.download-qr');
    Route::get('/api/barang/qr/{hash}', 'getBarangDetails')->name('barang.api-details');
});
```

### 6. Update BarangController
**File:** `app/Http/Controllers/BarangController.php`

- **store()** - Generate QR Code otomatis setelah barang dibuat
- **update()** - Regenerate QR Code saat data barang diupdate
- **destroy()** - Hapus file QR Code saat barang dihapus

### 7. View Publik Mobile-Friendly
**File:** `resources/views/fasilitas/barang/public-detail.blade.php`

Tampilan yang dioptimalkan untuk mobile dengan:
- Header dengan foto barang
- Informasi dasar barang (kategori, merk, jumlah, kondisi)
- Tanggal perolehan
- Lokasi barang (Gedung, Ruangan, Lantai)
- Keterangan tambahan
- Display QR Code
- Tombol Print dan Download QR

### 8. Update View Index Barang
**File:** `resources/views/fasilitas/barang/index.blade.php`

Menambahkan:
- Kolom QR Code di tabel list barang
- Preview QR Code thumbnail (40x40px)
- Modal untuk preview QR Code lebih besar
- Tombol akses detail publik

---

## 📍 URL Endpoints

### Public URLs

#### 1. Detail Barang (HTML)
```
GET /barang/qr/{hash}
```
- Menampilkan halaman detail barang dalam format HTML
- Tidak memerlukan autentikasi
- Responsive untuk mobile

**Contoh:**
```
https://bumisultan.app/barang/qr/abc123def456_1702384000
```

#### 2. Download QR Code
```
GET /barang/qr/{hash}/download
```
- Download file QR Code sebagai PNG
- Nama file: `QR_[KODE_BARANG].png`

**Contoh:**
```
https://bumisultan.app/barang/qr/abc123def456_1702384000/download
```

#### 3. API Endpoint (JSON)
```
GET /api/barang/qr/{hash}
```
- Mengembalikan data barang dalam format JSON
- Cocok untuk integrasi dengan aplikasi lain

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "kode_barang": "GD01-RU01-BR01",
    "nama_barang": "Meja Kerja",
    "kategori": "Perabotan",
    "merk": "Furnita",
    "jumlah": 5,
    "satuan": "Unit",
    "kondisi": "Baik",
    "status_barang": "Aktif",
    "tanggal_perolehan": "01-01-2023",
    "harga_perolehan": "1500000.00",
    "keterangan": "Meja kerja standar dengan 3 laci",
    "foto": "https://bumisultan.app/storage/barang/barang_1702384000.jpg",
    "qr_code": "https://bumisultan.app/storage/qr_codes/qr_code_1_1702384000.png",
    "ruangan": {
      "nama_ruangan": "Ruang Kerja A",
      "lantai": 2,
      "gedung": {
        "nama_gedung": "Gedung Utama",
        "alamat": "Jl. Sudirman No. 123"
      }
    },
    "created_at": "01-12-2024 10:30",
    "updated_at": "12-12-2024 15:45"
  }
}
```

---

## 🚀 Cara Kerja

### 1. Menambah Barang Baru
```
Admin/Karyawan → Masuk ke Menu Fasilitas Asset → Gedung → Ruangan → Tambah Barang
```

**Proses di Backend:**
1. Form barang disubmit ke `BarangController@store()`
2. Barang disimpan ke database
3. **Otomatis** dipanggil `QrCodeBarangService::generateQrCode($barang)`
4. QR Code digenerate berisi URL: `https://bumisultan.app/barang/qr/{hash}`
5. QR Code disimpan di `public/storage/qr_codes/`
6. User mendapat pesan sukses

### 2. Edit Barang
```
Admin/Karyawan → Klik Edit pada list barang → Update data → Simpan
```

**Proses di Backend:**
1. Data barang diupdate
2. **Otomatis** dipanggil `QrCodeBarangService::regenerateQrCode($barang)`
3. QR Code di-regenerate (tetap pakai hash yang sama)
4. User mendapat pesan sukses

### 3. Scan QR Code
```
User dengan HP → Buka Kamera HP → Arahkan ke QR Code → Klik Notifikasi
```

**Proses:**
1. HP membaca QR Code berisi URL publik
2. Membuka link ke `/barang/qr/{hash}`
3. Halaman detail barang langsung muncul
4. **Tidak perlu login**
5. User bisa melihat semua informasi barang
6. User bisa print atau download QR Code

### 4. Hapus Barang
```
Admin/Karyawan → Klik Delete pada list barang → Konfirmasi Hapus
```

**Proses di Backend:**
1. File QR Code dihapus dari storage
2. Record barang dihapus dari database

---

## 🎨 Tampilan Halaman Publik Detail Barang

### Header Section
- Foto barang besar dengan overlay gradient
- Nama barang
- Kode barang
- Status badge (Aktif)

### Info Section
- Kategori, Merk, Jumlah & Satuan
- Kondisi barang (badge dengan warna berbeda)
  - Hijau: Baik
  - Kuning: Rusak Ringan
  - Merah: Rusak Berat

### Lokasi Section
- 🏢 Gedung: Nama lengkap gedung
- 🚪 Ruangan: Nama ruangan + lantai
- 📌 Alamat: Alamat gedung

### QR Code Section
- Display QR Code ukuran 250x250px
- Teks penjelasan

### Footer Actions
- 🖨️ Button Print
- ⬇️ Button Download QR

### Meta Information
- Tanggal dibuat
- Tanggal di-update terakhir
- Notasi "Data publik - Dapat diakses oleh siapa saja"

---

## 📋 Database Structure

### Tabel `barangs` - Kolom Baru

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `qr_code_data` | text | Data URL untuk QR Code PNG (base64) |
| `qr_code_hash` | varchar(64) | Hash unik untuk URL publik (UNIQUE) |
| `qr_code_path` | varchar(255) | Path relatif file QR Code PNG |
| `status_barang` | enum | Aktif/Rusak Total/Hilang (default: Aktif) |

**Index:**
- `qr_code_hash` (UNIQUE) - untuk pencarian cepat

---

## 🔐 Keamanan

### 1. Hash Unik
- Setiap barang memiliki hash random 40 karakter + timestamp
- Format: `{40-char-random}_{timestamp}`
- Contoh: `a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t_1702384000`

### 2. Status Filtering
- Hanya barang dengan `status_barang = 'Aktif'` yang ditampilkan di public
- Barang dengan status 'Rusak Total' atau 'Hilang' menampilkan error 404

### 3. No SQL Injection
- Menggunakan Eloquent ORM dengan query builder
- Semua input sudah escape otomatis

### 4. No Authentication Required
- Halaman publik sengaja dibuat tanpa auth untuk kemudahan akses
- Tidak ada data sensitif yang ditampilkan (hanya info barang)

---

## 📊 File Storage

### Direktori QR Code
```
public/storage/qr_codes/
└── qr_code_1_1702384000.png
└── qr_code_2_1702384100.png
└── qr_code_3_1702384200.png
```

### Naming Convention
- Format: `qr_code_{barang_id}_{timestamp}.png`
- Contoh: `qr_code_5_1702384000.png`

---

## 🎯 Use Cases

### 1. Inventory Control
Admin menempel QR Code di barang fisik. Kapan saja ada pertanyaan tentang barang, cukup scan QR Code.

### 2. Asset Management
Visitor atau tamu yang ingin tahu detail barang dapat scan QR Code untuk melihat informasi lengkap.

### 3. Maintenance Tracking
Teknisi dapat scan QR Code untuk melihat kondisi barang dan riwayat pemeliharaan.

### 4. Export & Sharing
Admin dapat download QR Code dan di-print untuk ditempel di lokasi fisik barang.

---

## 🛠️ Setup & Migration

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Publish Package Untuk Composer
Pastikan `simplesoftwareio/simple-qrcode` sudah ter-install (sudah ada di composer.json):
```bash
composer install
```

### 3. Verifikasi Routes
```bash
php artisan route:list | grep barang
```

Seharusnya akan muncul:
```
GET  /barang/qr/{hash}
GET  /barang/qr/{hash}/download
GET  /api/barang/qr/{hash}
```

### 4. Test
```bash
# Buka halaman barang di admin
https://bumisultan.app/gedung/[encrypted]/ruangan/[encrypted]/barang

# Test public detail
https://bumisultan.app/barang/qr/abc123def456_1702384000

# Test API
https://bumisultan.app/api/barang/qr/abc123def456_1702384000
```

---

## 📝 Notes

### Existing Data
Jika sudah ada barang yang dibuat sebelum migrasi ini, QR Code belum ada.

**Solusi:**
Buat command untuk generate QR Code untuk barang yang sudah ada:
```bash
php artisan tinker

# Kemudian jalankan:
App\Models\Barang::all()->each(function($barang) {
    \App\Services\QrCodeBarangService::generateQrCode($barang);
});
```

### Performance
- QR Code dibuat saat save barang (sync)
- Untuk ~1000 barang, waktu generate ~0.5-1 detik per barang
- Jika perlu async, dapat menggunakan Queue

### Customization
- Ukuran QR Code dapat diubah di `QrCodeBarangService::generateQrCode()`
- Style halaman publik dapat dikustomisasi di `public-detail.blade.php`
- Warna kondisi dapat diubah di CSS yang sama

---

## ✅ Checklist Implementasi

- [x] Database migration dibuat
- [x] Model Barang updated dengan kolom baru
- [x] Service QrCodeBarangService dibuat
- [x] Public Controller dibuat
- [x] Public Routes ditambahkan
- [x] Private Controller updated (store/update/destroy)
- [x] View publik detail barang dibuat (mobile-friendly)
- [x] View index barang updated dengan QR Code column
- [x] Modal preview QR Code ditambahkan
- [x] JavaScript untuk QR preview ditambahkan
- [x] Dokumentasi lengkap dibuat

---

## 🎓 Developer Notes

### QR Code Package
- Package: `simplesoftwareio/simple-qrcode`
- Dokumentasi: https://github.com/SimpleSoftwareIO/simple-qrcode

### Generate Custom QR Code
```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$qrCode = QrCode::format('png')
    ->size(300)           // Ukuran
    ->margin(1)          // Margin
    ->generate($url);    // URL yang di-encode
```

### Regenerate Untuk Existing Data
Untuk barang yang sudah ada:
```php
use App\Models\Barang;
use App\Services\QrCodeBarangService;

$barang = Barang::find(1);
QrCodeBarangService::generateQrCode($barang);
```

---

## 📞 Support

Untuk pertanyaan atau issue:
1. Check dokumentasi ini dulu
2. Lihat error message di log file: `storage/logs/laravel.log`
3. Pastikan folder `public/storage/qr_codes` exist dan writable

---

**Dokumentasi Version:** 1.0  
**Created:** 12 Desember 2024  
**Last Updated:** 12 Desember 2024
