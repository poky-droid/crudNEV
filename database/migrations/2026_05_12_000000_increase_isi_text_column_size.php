<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change isi_text column to LONGTEXT to support large base64 images
        Schema::table('modul_konten', function (Blueprint $table) {
            // Drop the existing column constraint if any
            DB::statement('ALTER TABLE modul_konten MODIFY isi_text LONGTEXT COLLATE utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modul_konten', function (Blueprint $table) {
            DB::statement('ALTER TABLE modul_konten MODIFY isi_text LONGTEXT COLLATE utf8mb4_unicode_ci');
        });
    }
};
