<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Tambah kolom untuk menyimpan data QR Code
            $table->text('qr_code_data')->nullable()->after('foto')->comment('Data URL untuk QR Code PNG');
            $table->string('qr_code_hash', 64)->nullable()->unique()->after('qr_code_data')->comment('Hash unik untuk URL publik');
            $table->string('qr_code_path')->nullable()->after('qr_code_hash')->comment('Path file QR Code PNG');
            $table->enum('status_barang', ['Aktif', 'Rusak Total', 'Hilang'])->default('Aktif')->after('keterangan')->comment('Status barang untuk kontrol inventaris');
            
            // Index untuk pencarian cepat
            $table->index('qr_code_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropIndex(['qr_code_hash']);
            $table->dropColumn(['qr_code_data', 'qr_code_hash', 'qr_code_path', 'status_barang']);
        });
    }
};
