<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKurmerPerTp extends Model
{
    use HasFactory;
    protected $table = 'view_nilai_kurmer_pertp';
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
	public function tp_nilai(){
		return $this->hasOne(Tp_nilai::class, 'tp_id', 'tp_id');
	}
}
