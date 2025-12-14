<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;

class CheckBarangQr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:barang-qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Barang QR Code status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $barangs = Barang::all();
        
        if ($barangs->isEmpty()) {
            $this->warn('No barang found in database');
            return;
        }

        $this->info('=== Barang QR Code Status ===');
        
        foreach ($barangs as $barang) {
            $fileExists = $barang->qr_code_path && file_exists(public_path('storage/' . $barang->qr_code_path));
            $status = $fileExists ? '✅' : '⚠️';
            
            $this->info("
$status Barang ID: {$barang->id}
   Nama: {$barang->nama_barang}
   Hash: {$barang->qr_code_hash}
   Path: {$barang->qr_code_path}
   File Exists: " . ($fileExists ? 'YES' : 'NO'));
        }
    }
}
