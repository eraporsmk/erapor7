<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Deskripsi_mata_pelajaran extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'deskripsi_mata_pelajaran';
	protected $primaryKey = 'deskripsi_mata_pelajaran_id';
	protected $guarded = [];
	public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
