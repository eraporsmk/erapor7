<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Akt_pd extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'akt_pd';
	protected $primaryKey = 'akt_pd_id';
	protected $guarded = [];
	public function anggota_akt_pd(){
		return $this->hasMany(Anggota_akt_pd::class, 'akt_pd_id', 'akt_pd_id');
	}
	public function bimbing_pd(){
		return $this->hasMany(Bimbing_pd::class, 'akt_pd_id', 'akt_pd_id');
	}
	public function dudi(){
		return $this->hasOneThrough(
            Dudi::class,
            Mou::class,
            'mou_id', // Foreign key on users table...
            'dudi_id', // Foreign key on history table...
            'mou_id', // Local key on suppliers table...
            'dudi_id' // Local key on users table...
        );
	}
}
