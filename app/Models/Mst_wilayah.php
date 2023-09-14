<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mst_wilayah extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $timestamps = false;
    public $keyType = 'string';
	protected $table = 'ref.mst_wilayah';
	protected $primaryKey = 'kode_wilayah';
	protected $guarded = [];
	public function get_kabupaten(){
		return $this->hasOne(Mst_wilayah::class, 'kode_wilayah', 'mst_kode_wilayah');
    }
	public function parent()
    {
        return $this->belongsTo(Mst_wilayah::class, 'mst_kode_wilayah', 'kode_wilayah');
    }
    public function parrentRecursive()
    {
        return $this->parent()->with('parrentRecursive');
    }
}
