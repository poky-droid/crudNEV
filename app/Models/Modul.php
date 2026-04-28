<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model {
    protected $table = 'modul';
    protected $fillable = ['nama_modul','deskripsi','anggota_id'];
    public function anggota()     { return $this->belongsTo(Anggota::class, 'anggota_id'); }
    public function konten()      { return $this->hasMany(ModulKonten::class, 'modul_id'); }
    public function anggotaAkses(){ return $this->belongsToMany(Anggota::class, 'modul_anggota', 'modul_id', 'anggota_id'); }
}
