<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - {{ $barang->nama_barang }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 10px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header dengan foto barang */
        .header {
            position: relative;
            height: 300px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .header-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .header-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            padding: 20px;
            color: white;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 14px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .status-aktif {
            background: #10b981;
            color: white;
        }

        .status-rusak-ringan {
            background: #f59e0b;
            color: white;
        }

        .status-rusak-berat {
            background: #ef4444;
            color: white;
        }

        /* Content */
        .content {
            padding: 24px;
        }

        .section {
            margin-bottom: 28px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #667eea;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item.full {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            line-height: 1.4;
            word-break: break-word;
        }

        .kondisi {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            width: fit-content;
        }

        .kondisi.baik {
            background: #d1fae5;
            color: #065f46;
        }

        .kondisi.rusak-ringan {
            background: #fef3c7;
            color: #92400e;
        }

        .kondisi.rusak-berat {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Lokasi Section */
        .lokasi-card {
            background: #f9fafb;
            padding: 16px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .lokasi-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .lokasi-item:last-child {
            margin-bottom: 0;
        }

        .lokasi-icon {
            font-size: 20px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .lokasi-text h4 {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .lokasi-text p {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
        }

        .qr-code-image {
            max-width: 250px;
            width: 100%;
            height: auto;
            margin: 0 auto 16px;
            display: block;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            background: white;
        }

        .qr-section svg {
            max-width: 250px;
            width: 100%;
            height: auto;
            margin: 0 auto 16px;
            display: block;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            background: white;
        }

        .qr-text {
            font-size: 12px;
            color: #666;
            margin-bottom: 12px;
        }

        /* Footer Actions */
        .footer {
            padding: 16px 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #333;
        }

        .btn-secondary:active {
            transform: scale(0.98);
        }

        /* Informasi Tanggal */
        .meta {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        /* Empty State */
        .no-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 80px;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .footer {
                display: none;
            }
        }

        /* Mobile Optimization */
        @media (max-width: 480px) {
            .container {
                border-radius: 12px;
                margin: 5px;
            }

            .header {
                height: 250px;
            }

            .header-title {
                font-size: 20px;
            }

            .content {
                padding: 16px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* Responsive Typography */
        @media (max-width: 360px) {
            .header-title {
                font-size: 18px;
            }

            .info-value {
                font-size: 14px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header dengan Foto -->
        <div class="header">
            @if($barang->foto && file_exists(public_path('storage/barang/' . $barang->foto)))
                <img src="{{ asset('storage/barang/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}" class="header-image">
                <div class="header-overlay">
                    <div class="header-title">{{ $barang->nama_barang }}</div>
                    <div class="header-subtitle">
                        <span>🏷️</span> {{ $barang->kode_barang }}
                    </div>
                    <span class="status-badge status-aktif">✓ Aktif</span>
                </div>
            @else
                <div class="no-image">📦</div>
                <div class="header-overlay">
                    <div class="header-title">{{ $barang->nama_barang }}</div>
                    <div class="header-subtitle">
                        <span>🏷️</span> {{ $barang->kode_barang }}
                    </div>
                    <span class="status-badge status-aktif">✓ Aktif</span>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Informasi Dasar Barang -->
            <div class="section">
                <h2 class="section-title">📋 Informasi Barang</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">{{ $barang->kategori ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Merk</div>
                        <div class="info-value">{{ $barang->merk ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jumlah</div>
                        <div class="info-value">{{ $barang->jumlah }} {{ $barang->satuan }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kondisi</div>
                        <div class="kondisi @if($barang->kondisi === 'Baik') baik @elseif($barang->kondisi === 'Rusak Ringan') rusak-ringan @else rusak-berat @endif">
                            {{ $barang->kondisi }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tanggal Perolehan -->
            <div class="section">
                <h2 class="section-title">📅 Tanggal Perolehan</h2>
                <div class="info-item">
                    <div class="info-value">
                        @if($barang->tanggal_perolehan)
                            {{ $barang->tanggal_perolehan->format('d M Y') }}
                        @else
                            <span style="color: #999;">Tidak ada informasi tanggal</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="section">
                <h2 class="section-title">📍 Lokasi Barang</h2>
                <div class="lokasi-card">
                    <div class="lokasi-item">
                        <div class="lokasi-icon">🏢</div>
                        <div class="lokasi-text">
                            <h4>Gedung</h4>
                            <p>{{ $barang->ruangan->gedung->nama_gedung }}</p>
                        </div>
                    </div>
                    <div class="lokasi-item">
                        <div class="lokasi-icon">🚪</div>
                        <div class="lokasi-text">
                            <h4>Ruangan</h4>
                            <p>{{ $barang->ruangan->nama_ruangan }} @if($barang->ruangan->lantai)(Lantai {{ $barang->ruangan->lantai }})@endif</p>
                        </div>
                    </div>
                    @if($barang->ruangan->gedung->alamat)
                    <div class="lokasi-item">
                        <div class="lokasi-icon">📌</div>
                        <div class="lokasi-text">
                            <h4>Alamat</h4>
                            <p>{{ $barang->ruangan->gedung->alamat }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Keterangan -->
            @if($barang->keterangan)
            <div class="section">
                <h2 class="section-title">📝 Keterangan</h2>
                <div class="info-item full">
                    <div class="info-value">{{ $barang->keterangan }}</div>
                </div>
            </div>
            @endif

            <!-- QR Code -->
            <div class="section">
                <h2 class="section-title">🔲 QR Code</h2>
                <div class="qr-section">
                    @if($barang->qr_code_path && file_exists(public_path('storage/' . $barang->qr_code_path)))
                        @php
                            $fileExt = pathinfo($barang->qr_code_path, PATHINFO_EXTENSION);
                        @endphp
                        @if($fileExt === 'svg')
                            {!! file_get_contents(public_path('storage/' . $barang->qr_code_path)) !!}
                        @else
                            <img src="{{ asset('storage/' . $barang->qr_code_path) }}" alt="QR Code {{ $barang->kode_barang }}" class="qr-code-image">
                        @endif
                        <div class="qr-text">
                            Scan ulang QR Code untuk membuka halaman ini
                        </div>
                    @else
                        <div style="color: #999; padding: 20px;">QR Code belum tersedia</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer dengan Actions -->
        <div class="footer">
            <button class="btn btn-primary" onclick="window.print()">
                <span>🖨️</span> Print
            </button>
            @if($barang->qr_code_path && file_exists(public_path('storage/' . $barang->qr_code_path)))
            <a href="{{ route('barang.download-qr', ['hash' => $barang->qr_code_hash]) }}" class="btn btn-secondary">
                <span>⬇️</span> Download QR
            </a>
            @endif
        </div>

        <!-- Informasi Sistem -->
        <div class="meta">
            Informasi barang ini dibuat pada {{ $barang->created_at->format('d M Y H:i') }}<br>
            <small>Data publik - Dapat diakses oleh siapa saja</small>
        </div>
    </div>
</body>
</html>
