<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use App\Models\PinjamanHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResetPinjamanData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:reset {--backup : Buat backup sebelum reset}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Reset semua data pinjaman ke kondisi bersih (dengan backup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== RESET DATA PINJAMAN ===');
        $this->info('Waktu: ' . now()->format('d-m-Y H:i:s'));
        $this->line('');

        // Confirm
        if (!$this->confirm('PERHATIAN! Ini akan menghapus SEMUA data pinjaman. Lanjutkan?')) {
            $this->info('Dibatalkan.');
            return;
        }

        try {
            // 1. COUNT DATA
            $this->info('1. Menghitung data...');
            $countPinjaman = Pinjaman::count();
            $countCicilan = PinjamanCicilan::count();
            $countHistory = PinjamanHistory::count();
            
            $this->line("   - Pinjaman: <fg=yellow>$countPinjaman</>");
            $this->line("   - Cicilan: <fg=yellow>$countCicilan</>");
            $this->line("   - History: <fg=yellow>$countHistory</>");

            // 2. BACKUP
            if ($this->option('backup')) {
                $this->info('');
                $this->info('2. Backup data...');
                
                if (!Storage::exists('backup-pinjaman')) {
                    Storage::makeDirectory('backup-pinjaman');
                }
                
                $timestamp = now()->format('Y-m-d_H-i-s');
                
                if ($countPinjaman > 0) {
                    $jsonPath = "backup-pinjaman/pinjaman_backup_$timestamp.json";
                    $data = [
                        'backup_time' => $timestamp,
                        'total_pinjaman' => $countPinjaman,
                        'total_cicilan' => $countCicilan,
                        'total_history' => $countHistory,
                        'data' => [
                            'pinjaman' => Pinjaman::with(['karyawan', 'cicilan', 'history'])->get(),
                        ]
                    ];
                    
                    Storage::put($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $this->line("   ✓ Backup tersimpan: <fg=green>$jsonPath</>");
                }
            }

            // 3. DELETE DATA
            $this->info('');
            $this->info('3. Menghapus data...');
            
            if ($countPinjaman > 0) {
                PinjamanHistory::truncate();
                $this->line('   ✓ History dihapus');
                
                PinjamanCicilan::truncate();
                $this->line('   ✓ Cicilan dihapus');
                
                Pinjaman::truncate();
                $this->line('   ✓ Pinjaman dihapus');
            } else {
                $this->line('   (Tidak ada data untuk dihapus)');
            }

            // 4. RESET AUTO INCREMENT
            $this->info('');
            $this->info('4. Reset auto increment...');
            
            DB::statement('ALTER TABLE pinjaman AUTO_INCREMENT = 1');
            $this->line('   ✓ Pinjaman counter reset');
            
            DB::statement('ALTER TABLE pinjaman_cicilan AUTO_INCREMENT = 1');
            $this->line('   ✓ Cicilan counter reset');
            
            DB::statement('ALTER TABLE pinjaman_history AUTO_INCREMENT = 1');
            $this->line('   ✓ History counter reset');

            // 5. VERIFY
            $this->info('');
            $this->info('5. Verifikasi...');
            
            $verifyPinjaman = Pinjaman::count();
            $verifyCicilan = PinjamanCicilan::count();
            $verifyHistory = PinjamanHistory::count();
            
            $this->line("   - Pinjaman: <fg=green>$verifyPinjaman</> ✓");
            $this->line("   - Cicilan: <fg=green>$verifyCicilan</> ✓");
            $this->line("   - History: <fg=green>$verifyHistory</> ✓");

            $this->info('');
            $this->info('<fg=green>✓ RESET BERHASIL!</>');
            $this->line('Anda sekarang bisa mulai input pinjaman dari awal.');

        } catch (\Exception $e) {
            $this->error('✗ ERROR: ' . $e->getMessage());
            $this->line('Backtrace: ' . $e->getTraceAsString());
        }
    }
}
