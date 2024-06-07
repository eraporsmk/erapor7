<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Praktik_kerja_lapangan extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	protected $table = 'praktik_kerja_lapangan';
	protected $primaryKey = 'pkl_id';
	protected $guarded = [];
	protected $appends = ['tanggal_mulai_str', 'tanggal_selesai_str', 'nama_dudi'];
	public function getTanggalMulaiStrAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y');
	}
	public function getTanggalSelesaiStrAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
	}
	public function rombongan_belajar()
	{
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function akt_pd()
	{
		return $this->belongsTo(Akt_pd::class, 'akt_pd_id', 'akt_pd_id');
	}
	public function pd_pkl()
	{
		return $this->hasMany(Pd_pkl::class, 'pkl_id', 'pkl_id');
	}
	public function tp_pkl()
	{
		return $this->hasMany(Tp_pkl::class, 'pkl_id', 'pkl_id');
	}
	public function dudi(){
		return $this->hasOneThrough(
            Mou::class,
            Akt_pd::class,
            'akt_pd_id',
            'mou_id',
            'akt_pd_id',
            'mou_id'
        );
	}
	public function getNamaDudiAttribute()
	{
		return ($this->dudi) ? $this->dudi->nama_dudi : '';
	}
	public function guru()
	{
		return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
	}
	public function nilai_pkl()
	{
		return $this->hasMany(Nilai_pkl::class, 'pkl_id', 'pkl_id');
	}
}
