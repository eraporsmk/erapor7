<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Catatan_budaya_kerja extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'catatan_budaya_kerja';
	protected $primaryKey = 'catatan_budaya_kerja_id';
	protected $guarded = [];
	public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function budaya_kerja()
	{
		return $this->belongsTo(Budaya_kerja::class, 'budaya_kerja_id', 'budaya_kerja_id');
	}
}
