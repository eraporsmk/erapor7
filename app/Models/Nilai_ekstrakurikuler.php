<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_ekstrakurikuler extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'nilai_ekstrakurikuler';
	protected $primaryKey = 'nilai_ekstrakurikuler_id';
	protected $guarded = [];
	public function ekstrakurikuler(){
		return $this->hasOne(Ekstrakurikuler::class, 'ekstrakurikuler_id', 'ekstrakurikuler_id');
	}
    public function anggota_rombel(){
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function rombongan_belajar(){
		return $this->hasOneThrough(
            Rombongan_belajar::class,
            Anggota_rombel::class,
            'anggota_rombel_id', // Foreign key on history table...
            'rombongan_belajar_id', // Foreign key on users table...
            'anggota_rombel_id', // Local key on suppliers table...
            'rombongan_belajar_id' // Local key on users table...
        );
	}
    public function peserta_didik(){
		return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'anggota_rombel_id', // Foreign key on history table...
            'peserta_didik_id', // Foreign key on users table...
            'anggota_rombel_id', // Local key on suppliers table...
            'peserta_didik_id' // Local key on users table...
        );
	}
}
