<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Gelar_ptk extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'gelar_ptk';
	protected $primaryKey = 'gelar_ptk_id';
	protected $guarded = [];
	public function gelar(){
		return $this->hasOne(Gelar::class, 'gelar_akademik_id', 'gelar_akademik_id');
	}
	public function gelar_depan(){
		return $this->hasOne(Gelar::class, 'gelar_akademik_id', 'gelar_akademik_id')->where('posisi_gelar', 1);
	}
	public function gelar_belakang(){
		return $this->hasOne(Gelar::class, 'gelar_akademik_id', 'gelar_akademik_id')->where('posisi_gelar', 2);
	}
}
