<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Rencana_budaya_kerja extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rencana_budaya_kerja';
	protected $primaryKey = 'rencana_budaya_kerja_id';
	protected $guarded = [];
	
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rombongan_belajar()
	{
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function aspek_budaya_kerja()
	{
		return $this->hasMany(Aspek_budaya_kerja::class, 'rencana_budaya_kerja_id', 'rencana_budaya_kerja_id');
	}
	public function catatan_budaya_kerja()
	{
		return $this->hasOne(Catatan_budaya_kerja::class, 'rencana_budaya_kerja_id', 'rencana_budaya_kerja_id');
	}
	public function all_catatan_budaya_kerja()
	{
		return $this->hasMany(Catatan_budaya_kerja::class, 'rencana_budaya_kerja_id', 'rencana_budaya_kerja_id');
	}
}
