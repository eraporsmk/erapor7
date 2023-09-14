<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_akhir extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'nilai_akhir';
	protected $primaryKey = 'nilai_akhir_id';
	protected $guarded = [];
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id')->whereNotNull('kelompok_id');
	}
	public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
}
