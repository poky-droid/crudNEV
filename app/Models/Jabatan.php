<?php
// app/Models/Jabatan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model {
    public $timestamps = false;
    protected $table = 'jabatan';
    protected $fillable = ['nama_jabatan'];
    public function anggota() { return $this->hasMany(Anggota::class, 'jabatan_id'); }
}
