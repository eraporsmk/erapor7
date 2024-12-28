<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Guru extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'guru';
	protected $primaryKey = 'guru_id';
	protected $guarded = [];
	protected $appends = ['nama_lengkap', 'tanggal_lahir_indo'];

	public function sekolah()
	{
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function getTanggalLahirIndoAttribute()
	{
		return (isset($this->attributes['tanggal_lahir'])) ? Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y') : '';
	}
	public function rombongan_belajar(){
		return $this->hasOne(Rombongan_belajar::class, 'guru_id', 'guru_id')->where('semester_id', session('semester_aktif'))->where('jenis_rombel', 1);
	}
	public function getNamaLengkapAttribute()
	{
		$gelar_depan = '';
		$gelar_belakang = '';
		if($this->gelar_depan()->exists()){
			$gelar_depan = $this->gelar_depan()->get()->unique()->implode('kode', '. ') . '. ';
		}
		if($this->gelar_belakang()->exists()){
			$gelar_belakang = ', ' . $this->gelar_belakang()->get()->unique()->implode('kode', '., ') . '.';
		}
		return $gelar_depan . strtoupper($this->attributes['nama']). $gelar_belakang;
	}
	public function gelar_depan(){
		return $this->hasManyThrough(
            Gelar::class,
            Gelar_ptk::class,
            'guru_id',
            'gelar_akademik_id',
            'guru_id',
            'gelar_akademik_id'
        )->where('posisi_gelar', 1)->whereNotIn('gelar_ptk.gelar_akademik_id', [9999, 99999])->orderBy('kode', 'desc');
	}
	public function gelar_belakang(){
		return $this->hasManyThrough(
            Gelar::class,
            Gelar_ptk::class,
            'guru_id',
            'gelar_akademik_id',
            'guru_id',
            'gelar_akademik_id'
        )->where('posisi_gelar', 2)->whereNotIn('gelar_ptk.gelar_akademik_id', [9999, 99999])->orderBy('gelar_akademik.created_at');
	}
	public function pengguna(){
		return $this->hasOne(User::class, 'guru_id', 'guru_id');
	}
	public function dudi(){
		return $this->hasOneThrough(
            Dudi::class,
            Asesor::class,
            'guru_id', // Foreign key on users table...
            'dudi_id', // Foreign key on history table...
            'guru_id', // Local key on suppliers table...
            'dudi_id' // Local key on users table...
        );
	}
	public function ptk_keluar(){
		return $this->hasOne(Ptk_keluar::class, 'guru_id', 'guru_id');
	}
	public function agama()
	{
		return $this->hasOne(Agama::class, 'agama_id', 'agama_id');
	}
	public function bimbing_pd()
	{
		return $this->hasOne(Bimbing_pd::class, 'guru_id', 'guru_id');
	}
}
