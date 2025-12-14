<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Services\QrCodeBarangService;
use Illuminate\Console\Command;

class GenerateAllQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrcode:generate-all {--force : Force regenerate all QR codes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for all barang items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting QR Code Generation...');

        $force = $this->option('force');
        $barangs = Barang::all();

        if ($barangs->isEmpty()) {
            $this->warn('⚠️  No barang items found in database');
            return 0;
        }

        $total = count($barangs);
        $success = 0;
        $skipped = 0;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        foreach ($barangs as $barang) {
            try {
                // Skip if already has QR and not forcing
                if (!$force && !empty($barang->qr_code_hash) && !empty($barang->qr_code_path)) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Generate QR code
                QrCodeBarangService::generateQrCode($barang);
                $success++;
            } catch (\Exception $e) {
                $this->line("\n❌ Failed for barang ID {$barang->id}: {$e->getMessage()}");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ QR Code Generation Complete!");
        $this->info("📊 Summary:");
        $this->info("   Total barang: $total");
        $this->info("   Generated: $success");
        $this->info("   Skipped: $skipped");

        if ($success > 0) {
            $this->info("   Storage: public/storage/qr_codes/");
        }

        return 0;
    }
}
