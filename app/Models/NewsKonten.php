<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NewsKonten extends Model {
    public $timestamps = false;
    protected $table = 'news_konten';
    protected $fillable = ['news_id','tipe','isi_text','isi_file','urutan'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    public function news() { return $this->belongsTo(News::class, 'news_id'); }
}
