<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ModulKonten extends Model
{
    protected $table    = 'modul_konten';
    protected $fillable = ['modul_id', 'tipe', 'isi_text', 'isi_file', 'urutan'];

    // ← Tambahkan ini — tabel tidak punya created_at / updated_at
    public $timestamps = false;

    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id');
    }

    public function getIsiFileUrlAttribute(): ?string
    {
        return $this->isi_file
            ? Storage::disk('s3')->url($this->isi_file)
            : null;
    }
}