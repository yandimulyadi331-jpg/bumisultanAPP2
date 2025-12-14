<?php
/**
 * Script untuk Backup dan Reset Data Pinjaman
 * 
 * BACKUP DATA:
 * - Export ke CSV untuk history
 * - Simpan di folder backup
 * 
 * RESET DATA:
 * - Hapus semua records dari tabel pinjaman
 * - Hapus semua records dari tabel pinjaman_cicilan
 * - Hapus semua records dari tabel pinjaman_history
 * - Reset auto-increment counter
 * 
 * RUN: php artisan tinker < backup_reset_pinjaman.php
 * atau: php backup_reset_pinjaman.php
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use App\Models\PinjamanHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Colors untuk output
class Color {
    const RESET = "\033[0m";
    const RED = "\033[91m";
    const GREEN = "\033[92m";
    const YELLOW = "\033[93m";
    const BLUE = "\033[94m";
    const CYAN = "\033[96m";
}

echo Color::CYAN . "\n=== BACKUP & RESET DATA PINJAMAN ===\n" . Color::RESET;
echo "Waktu: " . now()->format('d-m-Y H:i:s') . "\n\n";

try {
    // 1. COUNT DATA SEBELUM BACKUP
    echo Color::BLUE . "1. MENGHITUNG DATA SEBELUM BACKUP..." . Color::RESET . "\n";
    
    $countPinjaman = Pinjaman::count();
    $countCicilan = PinjamanCicilan::count();
    $countHistory = PinjamanHistory::count();
    
    echo "   - Pinjaman: " . Color::YELLOW . $countPinjaman . Color::RESET . "\n";
    echo "   - Cicilan: " . Color::YELLOW . $countCicilan . Color::RESET . "\n";
    echo "   - History: " . Color::YELLOW . $countHistory . Color::RESET . "\n\n";
    
    // 2. BACKUP KE CSV
    echo Color::BLUE . "2. BACKUP DATA KE CSV..." . Color::RESET . "\n";
    
    // Buat folder backup jika belum ada
    if (!Storage::exists('backup-pinjaman')) {
        Storage::makeDirectory('backup-pinjaman');
    }
    
    $timestamp = now()->format('Y-m-d_H-i-s');
    
    // Backup Pinjaman
    if ($countPinjaman > 0) {
        $pinjamanData = Pinjaman::with(['cicilan', 'history'])
            ->get()
            ->toArray();
        
        $csvPath = "backup-pinjaman/pinjaman_backup_$timestamp.csv";
        $csvContent = "ID,Nomor,Kategori,NIK Karyawan,Nama Peminjam,Status,Jumlah Disetujui,Total Terbayar,Sisa Pinjaman,Tanggal Pengajuan\n";
        
        foreach ($pinjamanData as $p) {
            $csvContent .= "\"{$p['id']}\",\"{$p['nomor_pinjaman']}\",\"{$p['kategori_peminjam']}\",\"{$p['karyawan_id']}\",\"{$p['nama_peminjam_lengkap']}\",\"{$p['status']}\",\"{$p['jumlah_disetujui']}\",\"{$p['total_terbayar']}\",\"{$p['sisa_pinjaman']}\",\"{$p['tanggal_pengajuan']}\"\n";
        }
        
        Storage::put($csvPath, $csvContent);
        echo "   ✓ Pinjaman backup: " . Color::GREEN . $csvPath . Color::RESET . "\n";
    }
    
    // Backup detail ke JSON untuk reference lengkap
    if ($countPinjaman > 0) {
        $jsonPath = "backup-pinjaman/pinjaman_detail_$timestamp.json";
        $jsonContent = json_encode([
            'timestamp' => $timestamp,
            'pinjaman' => Pinjaman::with(['karyawan', 'cicilan', 'history'])->get(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        Storage::put($jsonPath, $jsonContent);
        echo "   ✓ Detail backup: " . Color::GREEN . $jsonPath . Color::RESET . "\n";
    }
    
    // 3. RESET DATA (DELETE)
    echo "\n" . Color::BLUE . "3. MENGHAPUS DATA (RESET)..." . Color::RESET . "\n";
    
    if ($countPinjaman > 0) {
        PinjamanHistory::truncate();
        echo "   ✓ Hapus history\n";
        
        PinjamanCicilan::truncate();
        echo "   ✓ Hapus cicilan\n";
        
        Pinjaman::truncate();
        echo "   ✓ Hapus pinjaman\n";
    }
    
    // 4. RESET AUTO INCREMENT
    echo "\n" . Color::BLUE . "4. RESET AUTO INCREMENT..." . Color::RESET . "\n";
    
    DB::statement('ALTER TABLE pinjaman AUTO_INCREMENT = 1');
    echo "   ✓ Pinjaman auto increment reset\n";
    
    DB::statement('ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1');
    echo "   ✓ Cicilan auto increment reset\n";
    
    DB::statement('ALTER TABLE pinjaman_history AUTO_INCREMENT = 1');
    echo "   ✓ History auto increment reset\n";
    
    // 5. VERIFIKASI
    echo "\n" . Color::BLUE . "5. VERIFIKASI DATA SETELAH RESET..." . Color::RESET . "\n";
    
    $verifyPinjaman = Pinjaman::count();
    $verifyCicilan = PinjamanCicilan::count();
    $verifyHistory = PinjamanHistory::count();
    
    echo "   - Pinjaman: " . Color::GREEN . $verifyPinjaman . Color::RESET . " ✓\n";
    echo "   - Cicilan: " . Color::GREEN . $verifyCicilan . Color::RESET . " ✓\n";
    echo "   - History: " . Color::GREEN . $verifyHistory . Color::RESET . " ✓\n";
    
    echo "\n" . Color::GREEN . "✓ RESET BERHASIL!" . Color::RESET . "\n";
    echo Color::YELLOW . "Backup tersimpan di: storage/app/backup-pinjaman/\n" . Color::RESET;
    echo "Anda sekarang bisa mulai input pinjaman dari awal.\n\n";
    
} catch (Exception $e) {
    echo Color::RED . "\n✗ ERROR: " . $e->getMessage() . Color::RESET . "\n";
    echo "Backtrace:\n" . $e->getTraceAsString() . "\n";
}
?>
