<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capaian_pembelajaran extends Model
{
    use HasFactory;
    public $incrementing = false;
	public $timestamps = false;
	protected $table = 'ref.capaian_pembelajaran';
	protected $primaryKey = 'cp_id';
	protected $guarded = [];
	public function mata_pelajaran(){
		return $this->hasOne(Mata_pelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
    public function tp()
    {
        return $this->hasMany(Tujuan_pembelajaran::class, 'cp_id', 'cp_id');
    }
}
