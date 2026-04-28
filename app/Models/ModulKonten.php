<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModulKonten extends Model {
    public $timestamps = false;
    protected $table = 'modul_konten';
    protected $fillable = ['modul_id','tipe','isi_text','isi_file','urutan'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    public function modul() { return $this->belongsTo(Modul::class, 'modul_id'); }
}
