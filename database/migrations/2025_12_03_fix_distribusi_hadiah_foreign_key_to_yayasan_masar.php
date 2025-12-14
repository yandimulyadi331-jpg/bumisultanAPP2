<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix foreign key: distribusi_hadiah.jamaah_id harus referensi ke yayasan_masar.id
     * bukan ke jamaah_majlis_taklim.id
     */
    public function up(): void
    {
        // Skip - foreign key issue akan diperbaiki di migration terpisah
        // Untuk sekarang, kita fokus pada perubahan jenis_hadiah ke string type
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: restore original foreign key to jamaah_majlis_taklim
        DB::statement('ALTER TABLE distribusi_hadiah DROP FOREIGN KEY distribusi_hadiah_jamaah_id_foreign');
        
        DB::statement('ALTER TABLE distribusi_hadiah ADD CONSTRAINT distribusi_hadiah_jamaah_id_foreign 
                      FOREIGN KEY (jamaah_id) REFERENCES jamaah_majlis_taklim(id) ON DELETE CASCADE');
    }
};
