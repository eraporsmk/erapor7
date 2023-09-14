<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Bobot_keterampilan extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'bobot_keterampilan';
	protected $primaryKey = 'bobot_keterampilan_id';
	protected $guarded = [];
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
    }
	public function metode(){
		return $this->hasOne(Teknik_penilaian::class, 'teknik_penilaian_id', 'metode_id');
    }
}
