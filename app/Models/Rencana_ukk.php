<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Rencana_ukk extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'rencana_ukk';
	protected $primaryKey = 'rencana_ukk_id';
	protected $guarded = [];
	public function guru_internal(){
		return $this->hasOne(Guru::class, 'guru_id', 'internal');
	}
	public function guru_eksternal(){
		return $this->hasOne(Guru::class, 'guru_id', 'eksternal')->with('dudi');
	}
	public function paket_ukk(){
		return $this->hasOne(Paket_ukk::class, 'paket_ukk_id', 'paket_ukk_id');
	}
	public function nilai_ukk(){
		return $this->hasOne(Nilai_ukk::class, 'rencana_ukk_id', 'rencana_ukk_id');
	}
	public function pd()
	{
		return $this->hasManyThrough(
			Peserta_didik::class, 
			Nilai_ukk::class,
			'rencana_ukk_id',
			'peserta_didik_id',
			'rencana_ukk_id',
			'peserta_didik_id',
		);
	}
}
