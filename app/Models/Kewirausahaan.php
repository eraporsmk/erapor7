<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Kewirausahaan extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'kewirausahaan';
	protected $primaryKey = 'kewirausahaan_id';
	protected $guarded = [];
	public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
    }
    public function anggota_kewirausahaan(){
        return $this->hasMany(Anggota_kewirausahaan::class, 'kewirausahaan_id', 'kewirausahaan_id');
    }
    public function nama_anggota()
    {
        $nama_siswa = strtoupper($this->anggota_rombel->siswa->nama);
        return $nama_siswa.'<br>'.implode('<br>', $this->anggota->map(function ($anggota) {
            return strtoupper($anggota->anggota_rombel->siswa->nama);
        })->toArray());
    }
    public function peserta_didik(){
        return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'anggota_rombel_id', // Foreign key on the cars table...
            'peserta_didik_id', // Foreign key on the owners table...
            'anggota_rombel_id', // Local key on the mechanics table...
            'peserta_didik_id' // Local key on the cars table...
        );
    }
}
