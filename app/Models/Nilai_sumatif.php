<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_sumatif extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_sumatif';
	protected $primaryKey = 'nilai_sumatif_id';
	protected $guarded = [];
	public function pembelajaran(){
        return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
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
