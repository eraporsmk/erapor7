<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Sekolah extends Model
{
    use HasFactory;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'sekolah';
	protected $primaryKey = 'sekolah_id';
	protected $guarded = [];

	public function kepala_sekolah(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function kasek(){
		return $this->hasOneThrough(
            Guru::class,
            Kasek::class,
            'sekolah_id', // Foreign key on the cars table...
            'guru_id', // Foreign key on the owners table...
            'sekolah_id', // Local key on the mechanics table...
            'guru_id' // Local key on the cars table...
        );
		return $this->hasOne(Kasek::class, 'guru_id', 'guru_id');
	}
	public function ptk()
	{
		return $this->hasMany(Guru::class, 'sekolah_id', 'sekolah_id');
	}
	/**
	 * Get the user that owns the Sekolah
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'sekolah_id', 'sekolah_id');
	}
	
	public function rombongan_belajar()
	{
		return $this->hasMany(Rombongan_belajar::class, 'sekolah_id', 'sekolah_id');
	}
	public function peserta_didik(){
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id');
	}
	public function anggota_rombel(){
		return $this->hasMany(Anggota_rombel::class, 'sekolah_id', 'sekolah_id');
	}
	public function pd_aktif()
	{
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id')->whereHas('anggota_rombel', function(Builder $query){
			$query->whereHas('rombongan_belajar', function(Builder $query){
				$query->where('jenis_rombel', 1);
				$query->where('semester_id', request()->semester_id);
			});
			$query->where('semester_id', request()->semester_id);
		});
	}
	
	public function pd_keluar()
	{
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id')->whereDoesntHave('anggota_rombel', function(Builder $query){
			$query->whereHas('rombongan_belajar', function(Builder $query){
				//$query->where('jenis_rombel', 1);
				$query->where('semester_id', request()->semester_id);
			});
			$query->where('semester_id', request()->semester_id);
		});
	}
	public function pembelajaran()
	{
		return $this->hasMany(Pembelajaran::class, 'sekolah_id', 'sekolah_id');
	}
	public function ekstrakurikuler()
	{
		return $this->hasMany(Rombongan_belajar::class, 'sekolah_id', 'sekolah_id')->where('jenis_rombel', 51);
	}
	public function anggota_ekskul()
	{
		return $this->hasMany(Anggota_rombel::class, 'sekolah_id', 'sekolah_id')->whereHas('rombongan_belajar', function(Builder $query){
			$query->where('jenis_rombel', 51);
		});
	}
	public function dudi()
	{
		return $this->hasMany(Dudi::class, 'sekolah_id', 'sekolah_id');
	}
	public function mou()
	{
		return $this->hasMany(Mou::class, 'sekolah_id', 'sekolah_id');
	}
	public function rencana_penilaian()
    {
        return $this->hasManyThrough(
            Rencana_penilaian::class,
            Pembelajaran::class,
            'sekolah_id', // Foreign key on the environments table...
            'pembelajaran_id', // Foreign key on the deployments table...
            'sekolah_id', // Local key on the projects table...
            'pembelajaran_id' // Local key on the environments table...
        );
    }
	public function rencana_pengetahuan(){
		return $this->rencana_penilaian()->where('kompetensi_id', 1);
	}
	public function rencana_keterampilan(){
		return $this->rencana_penilaian()->where('kompetensi_id', 2);
	}
	public function kd_nilai(){
		return $this->hasMany(Kd_nilai::class, 'sekolah_id', 'sekolah_id');
	}
	public function nilai_pengetahuan(){
		return $this->kd_nilai()->whereHas('rencana_penilaian', function($query){
			$query->where('kompetensi_id', 1);
		});
	}
	public function nilai_keterampilan(){
		return $this->kd_nilai()->whereHas('rencana_penilaian', function($query){
			$query->where('kompetensi_id', 2);
		});
	}
	public function nilai_akhir(){
		return $this->hasMany(Nilai_akhir::class, 'sekolah_id', 'sekolah_id');
	}
	public function cp(){
		return $this->hasMany(Deskripsi_mata_pelajaran::class, 'sekolah_id', 'sekolah_id');
	}
	public function nilai_projek(){
		return $this->hasMany(Catatan_budaya_kerja::class, 'sekolah_id', 'sekolah_id');
	}
}
