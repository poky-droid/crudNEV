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
        Schema::create('modul_konten', function (Blueprint $table) {
    $table->id();

    $table->foreignId('modul_id')
        ->constrained('modul')
        ->onDelete('cascade');

    $table->enum('tipe', ['text', 'image', 'file']);
    $table->longText('isi_text')->nullable();
    $table->string('isi_file')->nullable();
    $table->integer('urutan')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_konten');
    }
};
