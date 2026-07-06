<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modul', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn('anggota_id');

            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::table('modul', function (Blueprint $table) {
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
};