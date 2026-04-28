<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model {
    public $timestamps = false;
    protected $table = 'divisi';
    protected $fillable = ['nama_divisi'];
    public function anggota() { return $this->hasMany(Anggota::class, 'divisi_id'); }
}
