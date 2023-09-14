<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Asesor extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'asesor';
	protected $primaryKey = 'asesor_id';
	protected $guarded = [];
	public function guru(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function dudi(){
		return $this->hasOne(Dudi::class, 'dudi_id', 'dudi_id');
	}
}
