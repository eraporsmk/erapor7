<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggota_rombel extends Model
{
	use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
	//use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'anggota_rombel';
	protected $primaryKey = 'anggota_rombel_id';
	protected $guarded = [];
	
	public function rombongan_belajar()
	{
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function nilai_kd()
	{
		return $this->hasMany(Nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_tp()
	{
		return $this->hasMany(Nilai_tp::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_sumatif()
	{
		return $this->hasMany(Nilai_sumatif::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_sumatif_semester()
	{
		return $this->hasOne(Nilai_sumatif::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('jenis', 'na');
	}
	public function peserta_didik()
	{
		return $this->hasOne(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function nilai_remedial(){
		return $this->hasOne(Nilai_remedial::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function all_nilai_remedial(){
		return $this->hasMany(Nilai_remedial::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_kd_pengetahuan(){
		return $this->hasMany(NilaiPengetahuanPerKd::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 1);
	}
	public function nilai_kd_keterampilan(){
		return $this->hasMany(NilaiKeterampilanPerKd::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 2);
	}
	public function nilai_kd_pk(){
		return $this->hasMany(NilaiPkPerKd::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 3)->with('kd_nilai.kompetensi_dasar');
	}
	public function nilai_kd_pk_tertinggi(){
		return $this->hasOne(NilaiPkPerKd::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 3)->with('kd_nilai.kompetensi_dasar')->orderBy('jml_nilai', 'desc');
	}
	public function nilai_kd_pk_terendah(){
		return $this->hasOne(NilaiPkPerKd::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 3)->with('kd_nilai.kompetensi_dasar')->orderBy('jml_nilai', 'asc');
	}
	public function v_nilai_akhir_p(){
		return $this->hasOne(NilaiAkhirPengetahuan::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function v_nilai_akhir_k(){
		return $this->hasOne(NilaiAkhirKeterampilan::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function v_nilai_akhir_pk(){
		return $this->hasOne(NilaiAkhirPk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function catatan_budaya_kerja(){
		return $this->hasOne(Catatan_budaya_kerja::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function all_catatan_budaya_kerja(){
		return $this->hasMany(Catatan_budaya_kerja::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function catatan_ppk(){
		return $this->hasMany(Catatan_ppk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function single_catatan_ppk(){
		return $this->hasOne(Catatan_ppk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function catatan_wali(){
		return $this->hasMany(Catatan_wali::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function single_catatan_wali(){
		return $this->hasOne(Catatan_wali::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function single_deskripsi_mata_pelajaran(){
		return $this->hasOne(Deskripsi_mata_pelajaran::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function deskripsi_mata_pelajaran(){
		return $this->hasMany(Deskripsi_mata_pelajaran::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function deskripsi_sikap(){
		return $this->hasMany(Deskripsi_sikap::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function kenaikan_kelas(){
		return $this->hasMany(Kenaikan_kelas::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function single_kenaikan_kelas(){
		return $this->hasOne(Kenaikan_kelas::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function kewirausahaan(){
		return $this->hasMany(Kewirausahaan::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_akhir(){
		return $this->hasMany(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_akhir_mapel(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_budaya_kerja(){
		return $this->hasMany(Nilai_budaya_kerja::class, 'anggota_rombel_id', 'anggota_rombel_id')->whereNull('guru_id');
	}
	public function nilai_budaya_kerja_guru(){
		return $this->hasMany(Nilai_budaya_kerja::class, 'anggota_rombel_id', 'anggota_rombel_id')->whereNotNull('guru_id');
	}
	public function single_nilai_ekstrakurikuler(){
		return $this->hasOne(Nilai_ekstrakurikuler::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_ekstrakurikuler(){
		return $this->hasMany(Nilai_ekstrakurikuler::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_rapor(){
		return $this->hasMany(Nilai_rapor::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_sikap(){
		return $this->hasMany(Nilai_sikap::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_ukk(){
		return $this->hasMany(Nilai_ukk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_un(){
		return $this->hasMany(Nilai_un::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_us(){
		return $this->hasMany(Nilai_us::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function prakerin(){
		return $this->hasMany(Prakerin::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function single_prakerin(){
		return $this->hasOne(Prakerin::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function prestasi(){
		return $this->hasMany(Prestasi::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_rapor_pk(){
		return $this->hasOne(Nilai_rapor::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function absensi(){
		return $this->hasOne(Absensi::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function anggota_ekskul(){
		return $this->hasManyThrough(
            Anggota_rombel::class,
            Peserta_didik::class,
            'peserta_didik_id', // Foreign key on history table...
            'peserta_didik_id', // Foreign key on users table...
            'peserta_didik_id', // Local key on suppliers table...
            'peserta_didik_id' // Local key on users table...
        );
	}
	public function kenaikan(){
		return $this->hasOne(Kenaikan_kelas::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function all_nilai_ekskul(){
		return $this->hasManyThrough(
            Nilai_ekstrakurikuler::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on history table...
            'anggota_rombel_id', // Foreign key on users table...
            'peserta_didik_id', // Local key on suppliers table...
            'anggota_rombel_id' // Local key on users table...
        );
	}
	public function kehadiran(){
		return $this->hasOne(Absensi::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function all_prakerin(){
		return $this->hasMany(Prakerin::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_akhir_pengetahuan(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 1);
	}
	public function nilai_akhir_keterampilan(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 2);
	}
	public function nilai_akhir_pk(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 3);
	}
	public function nilai_akhir_kurmer(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 4);
	}
	public function nilai_akhir_induk(){
		return $this->hasOne(Nilai_akhir::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompetensi_id', 99);
	}
	public function nilai_ukk_satuan(){
		return $this->hasOne(Nilai_ukk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_akhir_legger(){
		return $this->hasOneDeep(
			Nilai_akhir::class, 
			[Rombongan_belajar::class, Pembelajaran::class],
			[
				'rombongan_belajar_id', // Foreign key on the "users" table.
				'rombongan_belajar_id',    // Foreign key on the "posts" table.
				'pembelajaran_id'     // Foreign key on the "comments" table.
			],
			[
				'rombongan_belajar_id', // Local key on the "countries" table.
				'rombongan_belajar_id', // Local key on the "users" table.
				'pembelajaran_id'  // Local key on the "posts" table.
			]
		);
	}
	public function nilai_rapor_legger(){
		return $this->hasOneDeep(
			Nilai_rapor::class, 
			[Rombongan_belajar::class, Pembelajaran::class],
			[
				'rombongan_belajar_id', // Foreign key on the "users" table.
				'rombongan_belajar_id',    // Foreign key on the "posts" table.
				'pembelajaran_id'     // Foreign key on the "comments" table.
			],
			[
				'rombongan_belajar_id', // Local key on the "countries" table.
				'rombongan_belajar_id', // Local key on the "users" table.
				'pembelajaran_id'  // Local key on the "posts" table.
			]
		);
	}
	public function capaian_kompeten(){
		return $this->hasMany(Tp_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function tp_nilai(){
		return $this->hasMany(Tp_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function tp_kompeten(){
		return $this->hasMany(Tp_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompeten', 1);
	}
	public function tp_inkompeten(){
		return $this->hasMany(Tp_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompeten', 0);
	}
	public function kd_kompeten(){
		return $this->hasMany(Kd_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompeten', 1);
	}
	public function kd_inkompeten(){
		return $this->hasMany(Kd_nilai::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('kompeten', 0);
	}
	public function nilai_pts()
	{
		return $this->hasOne(Nilai_pts::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
}
