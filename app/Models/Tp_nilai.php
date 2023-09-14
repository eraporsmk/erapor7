<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Tp_nilai extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'tp_nilai';
	protected $primaryKey = 'tp_nilai_id';
	protected $guarded = [];
	public function rencana_penilaian()
	{
		return $this->hasOne(Rencana_penilaian::class, 'rencana_penilaian_id', 'rencana_penilaian_id')->whereHas('pembelajaran', function($query){
			$query->where('semester_id', session('semester_aktif'));
		});
	}
	public function tp()
	{
		return $this->hasOne(Tujuan_pembelajaran::class, 'tp_id', 'tp_id');
	}
	public function kd()
	{
		return $this->hasOne(Kompetensi_dasar::class, 'kompetensi_dasar_id', 'kd_id');
	}
	public function nilai_tp()
	{
		return $this->hasMany(Nilai::class, 'tp_nilai_id', 'tp_nilai_id');
	}
}
