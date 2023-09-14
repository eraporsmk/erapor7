<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Kd_nilai extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'kd_nilai';
	protected $primaryKey = 'kd_nilai_id';
	protected $guarded = [];
	public function rencana_penilaian()
	{
		return $this->hasOne(Rencana_penilaian::class, 'rencana_penilaian_id', 'rencana_penilaian_id')->whereHas('pembelajaran', function($query){
			$query->where('semester_id', session('semester_aktif'));
		});
	}
	public function kompetensi_dasar()
	{
		return $this->hasOne(Kompetensi_dasar::class, 'kompetensi_dasar_id', 'kompetensi_dasar_id');
	}
	public function nilai()
	{
		return $this->hasMany(Nilai::class, 'kd_nilai_id', 'kd_nilai_id');
	}
}
