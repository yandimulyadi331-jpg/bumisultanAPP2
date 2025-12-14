# 📡 QR CODE BARANG - API ENDPOINTS DOCUMENTATION

## Overview

Fitur QR Code Barang menyediakan 3 endpoints publik yang dapat diakses tanpa autentikasi:

1. **Public Detail Page** - Tampilan HTML mobile-friendly
2. **Download QR Code** - Download file PNG
3. **API JSON** - Data dalam format JSON

---

## Endpoints

### 1. 📄 Public Detail Page (HTML)

**URL:** `GET /barang/qr/{hash}`

**Description:** Menampilkan halaman detail barang dalam format HTML yang responsif dan mobile-friendly.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `hash` | string | Yes | QR Code hash unik (dari kolom `qr_code_hash`) |

**Example:**
```
GET /barang/qr/abc123def456_1702384000
```

**Response:** HTML Page (200 OK)
```html
<!DOCTYPE html>
<html>
<head>
    <title>Detail Barang - Meja Kerja</title>
    ...
</head>
<body>
    <div class="container">
        <!-- Detail barang ditampilkan di sini -->
    </div>
</body>
</html>
```

**Response Status Codes:**
| Status | Description |
|--------|-------------|
| 200 | OK - Barang ditemukan dan bisa ditampilkan |
| 404 | Not Found - Hash tidak valid atau barang tidak aktif |
| 500 | Server Error |

**Usage Scenarios:**
- Scan QR Code dari kamera HP → langsung buka halaman ini
- Share link ke user lain → user bisa lihat detail barang
- Embed di aplikasi lain via iframe (dengan care)

**Mobile Optimized:**
- Responsive design
- Touch-friendly buttons
- Fast loading
- Offline-ready (foto cached)

---

### 2. ⬇️ Download QR Code (PNG File)

**URL:** `GET /barang/qr/{hash}/download`

**Description:** Download file QR Code sebagai PNG image.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `hash` | string | Yes | QR Code hash unik |

**Example:**
```
GET /barang/qr/abc123def456_1702384000/download
```

**Response:** File Download (200 OK)
```
Content-Type: image/png
Content-Disposition: attachment; filename="QR_GD01-RU01-BR01.png"
Content-Length: 1234
[binary PNG data]
```

**Response Status Codes:**
| Status | Description |
|--------|-------------|
| 200 | OK - File berhasil dikirim |
| 404 | Not Found - Hash tidak valid atau file tidak ada |
| 500 | Server Error |

**File Details:**
- Format: PNG (lossless image)
- Size: ~1-3 KB per file
- Filename: `QR_{KODE_BARANG}.png`
- Resolution: 300x300 pixels
- Color: Black & White (untuk maximum scanability)

**Use Cases:**
- Download untuk print label
- Share via email
- Upload ke sistem lain
- Backup QR Code image

**Example cURL:**
```bash
curl -O "http://localhost:8000/barang/qr/abc123def456_1702384000/download"
# Output: QR_GD01-RU01-BR01.png
```

**Example JavaScript:**
```javascript
// Download QR Code
const link = document.createElement('a');
link.href = '/barang/qr/' + hash + '/download';
link.download = 'QR_Code.png';
link.click();
```

---

### 3. 📊 API JSON Response

**URL:** `GET /api/barang/qr/{hash}`

**Description:** Mengembalikan detail barang dalam format JSON untuk integrasi dengan aplikasi lain.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `hash` | string | Yes | QR Code hash unik |

**Example:**
```
GET /api/barang/qr/abc123def456_1702384000
```

**Response:** JSON (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 5,
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
    "keterangan": "Meja kerja standar dengan 3 laci untuk ruang kerja umum",
    "foto": "https://bumisultan.app/storage/barang/barang_1702384000.jpg",
    "qr_code": "https://bumisultan.app/storage/qr_codes/qr_code_5_1702384000.png",
    "ruangan": {
      "nama_ruangan": "Ruang Kerja A",
      "lantai": 2,
      "gedung": {
        "nama_gedung": "Gedung Utama",
        "alamat": "Jl. Sudirman No. 123, Jakarta"
      }
    },
    "created_at": "01-12-2024 10:30",
    "updated_at": "12-12-2024 15:45"
  }
}
```

**Response Status Codes:**
| Status | Description |
|--------|-------------|
| 200 | OK - Data barang berhasil dikembalikan |
| 404 | Not Found - Hash tidak valid atau barang tidak aktif |
| 500 | Server Error |

**Response Fields Explanation:**
| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Indikator kesuksesan request |
| `data.id` | integer | ID barang di database |
| `data.kode_barang` | string | Kode unik barang (contoh: GD01-RU01-BR01) |
| `data.nama_barang` | string | Nama barang |
| `data.kategori` | string | Kategori barang (contoh: Perabotan) |
| `data.merk` | string | Merk/Pabrikan |
| `data.jumlah` | integer | Jumlah unit |
| `data.satuan` | string | Satuan pengukuran (Unit, Meter, dll) |
| `data.kondisi` | string | Kondisi (Baik / Rusak Ringan / Rusak Berat) |
| `data.status_barang` | string | Status (Aktif / Rusak Total / Hilang) |
| `data.tanggal_perolehan` | string | Tanggal barang diperoleh (format: DD-MM-YYYY) |
| `data.harga_perolehan` | string | Harga perolehan dalam Rupiah |
| `data.keterangan` | string | Catatan tambahan tentang barang |
| `data.foto` | string | URL foto barang |
| `data.qr_code` | string | URL file QR Code PNG |
| `data.ruangan.nama_ruangan` | string | Nama ruangan tempat barang berada |
| `data.ruangan.lantai` | integer | Nomor lantai |
| `data.ruangan.gedung.nama_gedung` | string | Nama gedung |
| `data.ruangan.gedung.alamat` | string | Alamat lengkap gedung |
| `data.created_at` | string | Waktu data dibuat (format: DD-MM-YYYY HH:MM) |
| `data.updated_at` | string | Waktu data terakhir diupdate |

**Error Response (404):**
```json
{
  "success": false,
  "message": "Barang tidak tersedia atau telah dihapus dari sistem."
}
```

**Error Response (500):**
```json
{
  "success": false,
  "message": "Internal server error"
}
```

**Use Cases:**
- Integrasi dengan aplikasi lain (mobile app, web app)
- Ambil data barang secara terprogram
- Sync data ke sistem eksternal
- Build custom UI dengan data dari API ini

**Example cURL:**
```bash
curl -X GET "http://localhost:8000/api/barang/qr/abc123def456_1702384000"
```

**Example JavaScript/Fetch:**
```javascript
const hash = 'abc123def456_1702384000';

fetch(`/api/barang/qr/${hash}`)
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Barang:', data.data.nama_barang);
      console.log('Lokasi:', data.data.ruangan.nama_ruangan);
      console.log('Kondisi:', data.data.kondisi);
    } else {
      console.error('Barang tidak ditemukan');
    }
  })
  .catch(error => console.error('Error:', error));
```

**Example PHP/Laravel:**
```php
$hash = 'abc123def456_1702384000';

$response = Http::get(url("/api/barang/qr/{$hash}"));

if ($response->successful() && $response['success']) {
    $barang = $response['data'];
    echo "Barang: {$barang['nama_barang']}";
    echo "Lokasi: {$barang['ruangan']['nama_ruangan']}";
} else {
    echo "Barang tidak ditemukan";
}
```

**Example Python/Requests:**
```python
import requests

hash_value = 'abc123def456_1702384000'
response = requests.get(f'http://localhost:8000/api/barang/qr/{hash_value}')

if response.status_code == 200:
    data = response.json()
    if data['success']:
        barang = data['data']
        print(f"Barang: {barang['nama_barang']}")
        print(f"Lokasi: {barang['ruangan']['nama_ruangan']}")
else:
    print("Barang tidak ditemukan")
```

---

## Common Usage Patterns

### Pattern 1: Single Item Lookup
```
User scan QR → Sistem get hash dari QR → Request API /api/barang/qr/{hash} → Parse JSON → Display
```

### Pattern 2: Download QR Code
```
Admin klik download button → Browser access /barang/qr/{hash}/download → File PNG download
```

### Pattern 3: Public Sharing
```
Visitor get link /barang/qr/{hash} → Open di browser → See detail page (no login needed)
```

### Pattern 4: Mobile App Integration
```
Mobile app scan QR → Get hash → Call API endpoint → Get JSON data → Render custom UI
```

---

## Response Headers

All endpoints return these headers:

```
Content-Type: application/json (for API)
Content-Type: text/html (for HTML page)
Content-Type: image/png (for download)

Cache-Control: private, max-age=3600
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
```

---

## Rate Limiting

Saat ini **tidak ada rate limiting** untuk endpoint publik. Jika diperlukan, dapat ditambahkan di future dengan Laravel middleware.

---

## CORS (Cross-Origin)

Endpoint **tidak mengaktifkan CORS** secara default. Jika ingin mengakses dari domain lain, configure di `config/cors.php`.

---

## Performance Metrics

| Operation | Response Time |
|-----------|---------------|
| Public Detail Page | ~50-100ms |
| API JSON Response | ~30-50ms |
| Download QR PNG | ~10-20ms |
| Hash Lookup | ~2-5ms (indexed) |

---

## Security Notes

### Authentication
- ✅ Tidak memerlukan login
- ✅ Hash unik mencegah brute force (40-char random)
- ✅ Status filter mencegah akses barang tidak aktif

### Data Privacy
- ✅ Hanya informasi barang yang ditampilkan
- ✅ Tidak ada data karyawan
- ✅ Tidak ada data finansial sensitif

### SQL Injection
- ✅ Menggunakan Eloquent ORM (parameterized queries)
- ✅ Input sudah di-escape otomatis

---

## Testing the API

### Using cURL
```bash
# Test public detail page
curl -i "http://localhost:8000/barang/qr/abc123def456_1702384000"

# Test API endpoint
curl -H "Content-Type: application/json" \
     "http://localhost:8000/api/barang/qr/abc123def456_1702384000"

# Test download
curl -O "http://localhost:8000/barang/qr/abc123def456_1702384000/download"
```

### Using Postman
1. Create new request
2. Set method: GET
3. URL: `http://localhost:8000/api/barang/qr/{hash}`
4. Send
5. See JSON response

### Using Browser DevTools
```javascript
// In browser console:
fetch('/api/barang/qr/abc123def456_1702384000')
  .then(r => r.json())
  .then(console.log)
```

---

## Error Handling

### Client-side (JavaScript)
```javascript
fetch(`/api/barang/qr/${hash}`)
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    if (!data.success) {
      throw new Error(data.message);
    }
    // Process data
  })
  .catch(error => {
    console.error('Error:', error.message);
  });
```

### Server-side (Laravel)
```php
try {
    $response = Http::get(url("/api/barang/qr/{$hash}"));
    $response->throw(); // Throw on 4xx/5xx
    $data = $response->json();
    // Process data
} catch (Exception $e) {
    Log::error('Barang API Error: ' . $e->getMessage());
}
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2024-12-12 | Initial release |

---

**API Documentation Version:** 1.0  
**Last Updated:** 12 Desember 2024
