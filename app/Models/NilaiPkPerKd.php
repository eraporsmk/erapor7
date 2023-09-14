<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPkPerKd extends Model
{
    use HasFactory;
    protected $table = 'view_nilai_pk_perkd';
	public function peserta_didik(){
        return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on Anggota_rombel table...
            'peserta_didik_id', // Foreign key on Siswa table...
            'anggota_rombel_id', // Local key on NilaiKeterampilanPerKd table...
            'anggota_rombel_id' // Local key on users table...
        );
    }
	public function kd_nilai(){
		return $this->hasOne(Kd_nilai::class, 'kompetensi_dasar_id', 'kompetensi_dasar_id');
	}
}
