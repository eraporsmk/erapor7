<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai';
	protected $primaryKey = 'nilai_id';
	protected $guarded = [];
	public function kd_nilai(){
        return $this->hasOne(Kd_nilai::class, 'kd_nilai_id', 'kd_nilai_id');
    }
	public function siswa(){
		return $this->hasOneThrough(
            Anggota_rombel::class,
            Peserta_didik::class,
            'peserta_didik_id',
            'peserta_didik_id',
            'anggota_rombel_id',
            'peserta_didik_id'
        );
	}
    public function anggota_rombel()
    {
        return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
    }
}
