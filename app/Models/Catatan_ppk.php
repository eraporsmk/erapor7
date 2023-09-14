<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Catatan_ppk extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'catatan_ppk';
	protected $primaryKey = 'catatan_ppk_id';
	protected $guarded = [];
	public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_karakter(){
		return $this->hasMany(Nilai_karakter::class, 'catatan_ppk_id', 'catatan_ppk_id');
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
