<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Anggota extends Authenticatable {
    protected $table = 'anggota';
    protected $fillable = ['nama','email','nim','jurusan','foto','password','divisi_id','jabatan_id'];
    protected $hidden   = ['password'];

    public function divisi()  { return $this->belongsTo(Divisi::class, 'divisi_id'); }
    public function jabatan() { return $this->belongsTo(Jabatan::class, 'jabatan_id'); }
    public function news()    { return $this->hasMany(News::class, 'anggota_id'); }
    public function moduls()  { return $this->hasMany(Modul::class, 'anggota_id'); }
    public function modulAkses() { return $this->belongsToMany(Modul::class, 'modul_anggota', 'anggota_id', 'modul_id'); }
    public function getFotoUrlAttribute(): string
{
    return $this->foto
        ? \Storage::disk('minio')->url($this->foto)
        : '/default-avatar.png';
}
}
