<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Modul extends Model {
    protected $table = 'modul';
    protected $fillable = ['nama_modul','slug','deskripsi','anggota_id'];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_modul);
            }
        });
    }
    
    public function anggota()     { return $this->belongsTo(Anggota::class, 'anggota_id'); }
    public function konten()      { return $this->hasMany(ModulKonten::class, 'modul_id'); }
    public function anggotaAkses(){ return $this->belongsToMany(Anggota::class, 'modul_anggota', 'modul_id', 'anggota_id'); }
}
