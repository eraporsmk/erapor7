<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Paket_ukk extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'ref.paket_ukk';
	protected $primaryKey = 'paket_ukk_id';
	protected $guarded = [];
	public function jurusan(){
		return $this->hasOne(Jurusan::class, 'jurusan_id', 'jurusan_id');
	}
	public function kurikulum(){
		return $this->hasOne(Kurikulum::class, 'kurikulum_id', 'kurikulum_id');
	}
	public function unit_ukk(){
		return $this->hasMany(Unit_ukk::class, 'paket_ukk_id', 'paket_ukk_id');
	}
	public function rencana_ukk(){
		return $this->hasMany(Rencana_ukk::class, 'paket_ukk_id', 'paket_ukk_id');
	}
}
