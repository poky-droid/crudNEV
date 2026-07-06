<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi many-to-many ke Modul lewat tabel pivot modul_kategori
     */
    public function moduls()
    {
        return $this->belongsToMany(Modul::class, 'modul_kategori');
    }
}