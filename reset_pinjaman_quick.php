<?php
/**
 * QUICK RESET PINJAMAN - Tanpa interaksi
 * RUN: php reset_pinjaman_quick.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use App\Models\PinjamanHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "\n=== RESET DATA PINJAMAN (QUICK MODE) ===\n";
echo "Waktu: " . date('d-m-Y H:i:s') . "\n\n";

try {
    // DISABLE FK
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // COUNT
    echo "1. Data sebelum reset:\n";
    $c1 = Pinjaman::count();
    $c2 = PinjamanCicilan::count();
    $c3 = PinjamanHistory::count();
    echo "   - Pinjaman: $c1\n";
    echo "   - Cicilan: $c2\n";
    echo "   - History: $c3\n\n";
    
    // BACKUP
    echo "2. Membuat backup...\n";
    if (!Storage::exists('backup-pinjaman')) {
        Storage::makeDirectory('backup-pinjaman');
    }
    
    $ts = date('Y-m-d_H-i-s');
    $backup = [
        'backup_time' => $ts,
        'pinjaman_count' => $c1,
        'cicilan_count' => $c2,
        'history_count' => $c3,
        'data' => [
            'pinjaman' => Pinjaman::with(['karyawan', 'cicilan', 'history'])->get(),
        ]
    ];
    
    $file = "backup-pinjaman/reset_backup_$ts.json";
    Storage::put($file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "   ✓ Backup: $file\n\n";
    
    // TRUNCATE
    echo "3. Menghapus data...\n";
    PinjamanHistory::truncate();
    echo "   ✓ History dihapus\n";
    PinjamanCicilan::truncate();
    echo "   ✓ Cicilan dihapus\n";
    Pinjaman::truncate();
    echo "   ✓ Pinjaman dihapus\n\n";
    
    // RESET AI
    echo "4. Reset auto increment...\n";
    DB::statement('ALTER TABLE pinjaman AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE pinjaman_history AUTO_INCREMENT = 1');
    echo "   ✓ Auto increment reset\n\n";
    
    // ENABLE FK KEMBALI
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    // VERIFY
    echo "5. Verifikasi:\n";
    echo "   - Pinjaman: " . Pinjaman::count() . " ✓\n";
    echo "   - Cicilan: " . PinjamanCicilan::count() . " ✓\n";
    echo "   - History: " . PinjamanHistory::count() . " ✓\n\n";
    
    echo "✓ RESET SELESAI!\n";
    echo "Backup tersimpan di: storage/app/backup-pinjaman/\n";
    echo "Anda bisa mulai input pinjaman dari awal.\n\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
?>
