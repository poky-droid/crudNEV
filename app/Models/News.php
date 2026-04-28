<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model {
    protected $table = 'news';
    protected $fillable = ['judul','deskripsi','gambar','anggota_id'];
    public function anggota() { return $this->belongsTo(Anggota::class, 'anggota_id'); }
    public function konten()  { return $this->hasMany(NewsKonten::class, 'news_id'); }
}
