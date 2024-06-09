<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Pd_pkl extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	protected $table = 'pd_pkl';
	protected $primaryKey = 'pd_pkl_id';
	protected $guarded = [];
	public function pd()
	{
		return $this->belongsTo(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function anggota_rombel()
	{
		return $this->belongsTo(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function praktik_kerja_lapangan()
	{
		return $this->belongsTo(Praktik_kerja_lapangan::class, 'pkl_id', 'pkl_id');
	}
	public function nilai_pkl(){
		return $this->hasMany(Nilai_pkl::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function absensi_pkl(){
		return $this->hasMany(Absensi_pkl::class, 'peserta_didik_id', 'peserta_didik_id');
	}
}
