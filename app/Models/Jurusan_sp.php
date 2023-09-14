<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurusan_sp extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'jurusan_sp';
	protected $primaryKey = 'jurusan_sp_id';
	protected $guarded = [];
	public function rombongan_belajar(){
		return $this->hasMany(Rombongan_belajar::class, 'jurusan_sp_id', 'jurusan_sp_id');
	}
	public function kurikulum(){
		return $this->hasMany(Kurikulum::class, 'jurusan_id', 'jurusan_id');
	}
	public function paket_ukk(){
		return $this->hasMany(Paket_ukk::class, 'jurusan_id', 'jurusan_id');
	}
}
