<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;

class GetBarangHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:barang-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get QR hash of first barang';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $barang = Barang::first();
        if ($barang) {
            echo "Barang ID: " . $barang->id . "\n";
            echo "Nama: " . $barang->nama_barang . "\n";
            echo "Hash: " . $barang->qr_code_hash . "\n";
            echo "Path: " . $barang->qr_code_path . "\n";
        } else {
            echo "No barang found\n";
        }
    }
}
