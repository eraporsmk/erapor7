<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Aspek_budaya_kerja extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'aspek_budaya_kerja';
	protected $primaryKey = 'aspek_budaya_kerja_id';
	protected $guarded = [];
	public function budaya_kerja()
	{
		return $this->belongsTo(Budaya_kerja::class, 'budaya_kerja_id', 'budaya_kerja_id');
	}
	public function elemen_budaya_kerja()
	{
		return $this->belongsTo(Elemen_budaya_kerja::class, 'elemen_id', 'elemen_id');
	}
	public function rencana_budaya_kerja()
	{
		return $this->belongsTo(Rencana_budaya_kerja::class, 'rencana_budaya_kerja_id', 'rencana_budaya_kerja_id');
	}
}
