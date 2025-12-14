<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Services\QrCodeBarangService;
use Illuminate\Console\Command;

class GenerateQrCodeForBarang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barang:generate-qr-codes {--only-empty : Generate QR codes hanya untuk barang yang belum memiliki QR code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes untuk semua barang yang belum memiliki QR code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Barang::where('status_barang', 'Aktif');

        if ($this->option('only-empty')) {
            $query->whereNull('qr_code_hash');
            $total = $query->count();
            $this->info("Mencari barang yang belum memiliki QR code...");
            $this->info("Total barang: {$total}");
        } else {
            $total = Barang::count();
            $this->info("Mencari semua barang...");
            $this->info("Total barang: {$total}");
        }

        if ($total === 0) {
            $this->warn('Tidak ada barang yang perlu di-generate QR code-nya.');
            return Command::SUCCESS;
        }

        $barangs = $query->get();
        $bar = $this->output->createProgressBar(count($barangs));
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($barangs as $barang) {
            try {
                QrCodeBarangService::generateQrCode($barang);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $this->line("\nGagal generate QR code untuk {$barang->kode_barang}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        
        $this->newLine();
        $this->info("✓ QR Code generation selesai!");
        $this->info("  - Berhasil: {$success}");
        if ($failed > 0) {
            $this->warn("  - Gagal: {$failed}");
        }

        return Command::SUCCESS;
    }
}
