<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Rencana_penilaian extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rencana_penilaian';
	protected $primaryKey = 'rencana_penilaian_id';
	protected $guarded = [];

	public function pembelajaran()
	{
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function kd_nilai(){
        return $this->hasMany(Kd_nilai::class, 'rencana_penilaian_id', 'rencana_penilaian_id');
    }
	public function tp_nilai(){
        return $this->hasMany(Tp_nilai::class, 'rencana_penilaian_id', 'rencana_penilaian_id');
    }
	public function metode(){
		return $this->hasOne(Teknik_penilaian::class, 'teknik_penilaian_id', 'metode_id');
	}
	public function teknik_penilaian(){
		return $this->hasOne(Teknik_penilaian::class, 'teknik_penilaian_id', 'metode_id');
	}
	public function rombongan_belajar(){
		return $this->hasOneThrough(
            Rombongan_belajar::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'rombongan_belajar_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'rombongan_belajar_id' // Local key on users table...
        );
	}
	public function nilai(){
		return $this->hasManyThrough(
            Nilai::class,
            Kd_nilai::class,
            'rencana_penilaian_id', // Foreign key on users table...
            'kd_nilai_id', // Foreign key on history table...
            'rencana_penilaian_id', // Local key on suppliers table...
            'kd_nilai_id' // Local key on users table...
        );
	}
	public function tp(){
		return $this->hasManyThrough(
            Tujuan_pembelajaran::class,
            Tp_nilai::class,
            'rencana_penilaian_id', // Foreign key on users table...
            'tp_id', // Foreign key on history table...
            'rencana_penilaian_id', // Local key on suppliers table...
            'tp_id' // Local key on users table...
        );
	}
}
