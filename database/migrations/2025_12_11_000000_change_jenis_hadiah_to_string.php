<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ubah kolom jenis_hadiah dari ENUM ke VARCHAR untuk mendukung input manual
     */
    public function up(): void
    {
        Schema::table('hadiah_majlis_taklim', function (Blueprint $table) {
            // Change jenis_hadiah from enum to string (varchar 100)
            $table->string('jenis_hadiah', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hadiah_majlis_taklim', function (Blueprint $table) {
            // Rollback ke enum jika diperlukan
            $table->enum('jenis_hadiah', ['sarung', 'peci', 'gamis', 'mukena', 'tasbih', 'sajadah', 'al_quran', 'buku', 'lainnya'])->change();
        });
    }
};
