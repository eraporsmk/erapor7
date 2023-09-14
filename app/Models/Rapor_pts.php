<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Rapor_pts extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'rapor_pts';
	protected $primaryKey = 'rapor_pts_id';
	protected $guarded = [];
	public function pembelajaran(){
		return $this->hasMany(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rencana_penilaian(){
		return $this->hasOne(Rencana_penilaian::class, 'rencana_penilaian_id', 'rencana_penilaian_id');
	}
	public function nilai(){
		return $this->hasManyThrough(
            Nilai::class,
            Kd_nilai::class,
            'rencana_penilaian_id', // Foreign key on history table...
            'kd_nilai_id', // Foreign key on users table...
            'rencana_penilaian_id', // Local key on suppliers table...
            'kd_nilai_id' // Local key on users table...
        );
	}
}
