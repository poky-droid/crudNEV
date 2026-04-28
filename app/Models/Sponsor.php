<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model {
    protected $table = 'sponsor';
    protected $fillable = ['nama','gambar','status','website','kontak','urutan'];
}
