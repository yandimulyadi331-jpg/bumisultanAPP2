<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeBarangService
{
    /**
     * Generate QR Code untuk barang
     * Menggunakan SVG format (tidak perlu imagick)
     * 
     * @param Barang $barang
     * @return void
     */
    public static function generateQrCode(Barang $barang): void
    {
        // Generate hash unik jika belum ada
        if (empty($barang->qr_code_hash)) {
            $barang->qr_code_hash = Str::random(40) . '_' . time();
        }

        // Buat URL publik untuk detail barang
        $publicUrl = route('barang.public-detail', ['hash' => $barang->qr_code_hash]);

        try {
            // Generate QR Code dengan format SVG (tidak perlu imagick)
            $qrCodeSvg = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->generate($publicUrl);

            // Simpan file QR Code SVG ke storage
            $qrFileName = 'qr_code_' . $barang->id . '_' . time() . '.svg';
            $qrPath = 'qr_codes/' . $qrFileName;
            
            // Ensure directory exists
            if (!file_exists(public_path('storage/qr_codes'))) {
                mkdir(public_path('storage/qr_codes'), 0755, true);
            }

            file_put_contents(
                public_path('storage/' . $qrPath),
                $qrCodeSvg
            );

            // Store SVG as data for later use
            $barang->qr_code_data = $qrCodeSvg;
            $barang->qr_code_path = $qrPath;
            $barang->save();

            \Log::info('QR Code generated successfully for barang: ' . $barang->id);
        } catch (\Exception $e) {
            // Jika gagal generate QR, simpan data tanpa file (fallback)
            \Log::warning('QR Code generation failed for barang: ' . $barang->id . ' - ' . $e->getMessage());
            
            // Tetap simpan hash untuk URL publik
            $barang->qr_code_hash = $barang->qr_code_hash ?? Str::random(40) . '_' . time();
            $barang->save();
        }
    }

    /**
     * Regenerate QR Code untuk barang (untuk keperluan update)
     * 
     * @param Barang $barang
     * @return void
     */
    public static function regenerateQrCode(Barang $barang): void
    {
        // Generate hash baru jika ingin reset
        // $barang->qr_code_hash = Str::random(40) . '_' . time();

        // Generate ulang QR Code dengan hash yang sama
        self::generateQrCode($barang);
    }

    /**
     * Delete QR Code file
     * 
     * @param Barang $barang
     * @return void
     */
    public static function deleteQrCode(Barang $barang): void
    {
        if (!empty($barang->qr_code_path) && file_exists(public_path('storage/' . $barang->qr_code_path))) {
            unlink(public_path('storage/' . $barang->qr_code_path));
        }
    }

    /**
     * Get QR Code URL
     * 
     * @param Barang $barang
     * @return string
     */
    public static function getQrCodeUrl(Barang $barang): string
    {
        if (!empty($barang->qr_code_path)) {
            return asset('storage/' . $barang->qr_code_path);
        }
        return '';
    }

    /**
     * Get QR Code HTML img tag
     * 
     * @param Barang $barang
     * @param string $alt
     * @param array $attributes
     * @return string
     */
    public static function getQrCodeHtml(Barang $barang, string $alt = 'QR Code Barang', array $attributes = []): string
    {
        if (empty($barang->qr_code_path)) {
            return '';
        }

        $url = asset('storage/' . $barang->qr_code_path);
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= " {$key}=\"{$value}\"";
        }

        return "<img src=\"{$url}\" alt=\"{$alt}\"{$attrs} />";
    }
}
