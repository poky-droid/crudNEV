<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    /**
     * Auto-generate slug dari nama_kategori, dan pastikan unique.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = static::generateUniqueSlug($kategori->nama_kategori);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama_kategori') && !$kategori->isDirty('slug')) {
                $kategori->slug = static::generateUniqueSlug($kategori->nama_kategori, $kategori->id);
            }
        });
    }

    /**
     * Generate slug unik, kalau bentrok tambahin suffix -1, -2, dst.
     */
    protected static function generateUniqueSlug(string $nama, $ignoreId = null): string
    {
        $slug = Str::slug($nama);
        $original = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;

            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    /**
     * Relasi many-to-many ke Modul lewat tabel pivot modul_kategori
     */
    public function moduls()
    {
        return $this->belongsToMany(Modul::class, 'modul_kategori');
    }
}