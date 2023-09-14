<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelajaran extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'pembelajaran';
	protected $primaryKey = 'pembelajaran_id';
	protected $guarded = [];
	public function guru(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function pengajar(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_pengajar_id');
	}
	public function mata_pelajaran(){
		return $this->hasOne(Mata_pelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function rombongan_belajar(){
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function rencana_pengetahuan(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 1);
	}
	public function rencana_keterampilan(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2);
	}
	public function rencana_pk(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3);
	}
	public function rencana_projek(){
		return $this->hasMany(Rencana_budaya_kerja::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rencana_projek_count(){
		return $this->hasManyThrough(
            Rencana_budaya_kerja::class,
			Pembelajaran::class,
			'induk_pembelajaran_id',
			'pembelajaran_id',
			'pembelajaran_id',
			'pembelajaran_id'
        );
	}
	public function projek(){
		return $this->hasOne(Rencana_budaya_kerja::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rencana_pengetahuan_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 1)->whereHas('nilai');
	}
	public function rencana_keterampilan_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2)->whereHas('nilai');
	}
	public function rencana_pk_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3)->whereHas('nilai');
	}
	public function nilai_akhir(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function nilai_akhir_pengetahuan(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 1);
	}
	public function nilai_akhir_keterampilan(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2);
	}
	public function nilai_akhir_pk(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3);
	}
	public function nilai_akhir_kurmer(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 4);
	}
	public function anggota_rombel(){
		return $this->hasManyThrough(
            Anggota_rombel::class,
			Rombongan_belajar::class,
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id'
        );
    }
	public function one_anggota_rombel(){
		return $this->hasOneThrough(
            Anggota_rombel::class,
			Rombongan_belajar::class,
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id'
        );
    }
	public function rencana_penilaian(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function kd_nilai()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        );
    }
	public function kd_nilai_capaian()
    {
        return $this->hasOneThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        );
    }
	public function kd_nilai_p()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 1);
    }
	public function kd_nilai_k()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 2);
    }
	public function kd_nilai_pk()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 3);
    }
	public function kelompok(){
		return $this->hasOne(Kelompok::class, 'kelompok_id', 'kelompok_id');
	}
	public function rapor_pts(){
		//return $this->hasOne(Rapor_pts::class, 'pembelajaran_id', 'pembelajaran_id');
		return $this->hasMany(Rapor_pts::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function nilai_akhir_p(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 1);
	}
	public function nilai_akhir_k(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 2);
	}
	public function nilai_akhir_is_pk(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 3);
	}
	public function nilai_rapor(){
		return $this->hasOne(Nilai_rapor::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function deskripsi_mata_pelajaran(){
		return $this->hasMany(Deskripsi_mata_pelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function single_deskripsi_mata_pelajaran(){
		return $this->hasOne(Deskripsi_mata_pelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function getSkmAttribute()
    {
		if($this->kkm){
			return $this->kkm;
		}
		$check_2018 = $this->check_2018();
		if ($check_2018) {
			$produktif = [4, 5, 9, 10, 13];
			$non_produktif = [1, 2, 3, 6, 7, 8, 11, 12, 99];
			if (in_array($this->kelompok_id, $produktif)) {
				return 65;
			} elseif (in_array($this->kelompok_id, $non_produktif)) {
				return 60;
			} else {
				return $this->kkm;
			}
		}
    }
	private function check_2018()
	{
		$semester_id = request()->semester_id;
		$tahun = substr($semester_id, 0, 4);
		if ($tahun >= 2018) {
			return true;
		} else {
			return false;
		}
	}
	public function induk(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'induk_pembelajaran_id');
	}
	public function tema()
	{
		return $this->hasMany(Pembelajaran::class, 'induk_pembelajaran_id', 'pembelajaran_id');
	}
	public function tp()
	{
		return $this->hasMany(Tujuan_pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function all_nilai_akhir_pengetahuan(){
		return $this->hasMany(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', 1);
	}
	public function all_nilai_akhir_kurmer(){
		return $this->hasMany(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', 4);
	}
	public function matev_rapor(){
		return $this->hasOne(Matev_rapor::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
