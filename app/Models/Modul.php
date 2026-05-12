<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Modul extends Model
{
    protected $table    = 'modul';
    protected $fillable = ['nama_modul', 'slug', 'deskripsi'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_modul);
            }
        });

        // Cascade delete: hapus file di MinIO sebelum modul dihapus
        static::deleting(function ($model) {
            foreach ($model->konten as $k) {
                if ($k->isi_file) {
                    Storage::disk('s3')->delete($k->isi_file);
                }
            }
        });
    }

    public function creators()  { return $this->belongsToMany(Anggota::class, 'modul_creators', 'modul_id', 'anggota_id')->withTimestamps(); }
    public function konten()    { return $this->hasMany(ModulKonten::class, 'modul_id')->orderBy('urutan'); }
}