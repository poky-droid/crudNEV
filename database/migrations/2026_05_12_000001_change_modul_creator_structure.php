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
        // Buat tabel pivot untuk multiple creators
        Schema::create('modul_creators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('modul')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikat
            $table->unique(['modul_id', 'anggota_id']);
        });

        // Drop modul_anggota table jika ada (akses pengguna)
        Schema::dropIfExists('modul_anggota');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_creators');
    }
};
