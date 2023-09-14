<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKeterampilanPerKd extends Model
{
    use HasFactory;
    protected $table = 'view_nilai_keterampilan_perkd';
	public function peserta_didik(){
        return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'anggota_rombel_id', 
            'peserta_didik_id', 
            'anggota_rombel_id', 
            'peserta_didik_id' 
        );
    }
	public function kd_nilai(){
		return $this->hasOne(Kd_nilai::class, 'kompetensi_dasar_id', 'kompetensi_dasar_id');
	}
}
