<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_budaya_kerja extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'nilai_budaya_kerja';
	protected $primaryKey = 'nilai_budaya_kerja_id';
	protected $guarded = [];
    public function budaya_kerja()
	{
		return $this->belongsTo(Budaya_kerja::class, 'budaya_kerja_id', 'budaya_kerja_id');
	}
    public function elemen_budaya_kerja()
	{
		return $this->belongsTo(Elemen_budaya_kerja::class, 'elemen_id', 'elemen_id');
	}
    public function guru()
	{
		return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
	}
    public function rencana_budaya_kerja()
    {
        return $this->hasOneThrough(
            Rencana_budaya_kerja::class, 
            Aspek_budaya_kerja::class,
            'aspek_budaya_kerja_id', // Foreign key on the cars table...
            'rencana_budaya_kerja_id', // Foreign key on the owners table...
            'aspek_budaya_kerja_id', // Local key on the mechanics table...
            'rencana_budaya_kerja_id' // Local key on the cars table...
        );
        return $this->belongsTo(Rencana_budaya_kerja::class, 'rencana_budaya_kerja_id', 'rencana_budaya_kerja_id');
    }
    public function aspek_budaya_kerja()
    {
        return $this->belongsTo(Aspek_budaya_kerja::class, 'aspek_budaya_kerja_id', 'aspek_budaya_kerja_id');
    }
    public function anggota_rombel()
    {
        return $this->belongsTo(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
    }
    public function opsi_budaya_kerja()
    {
        return $this->belongsTo(Opsi_budaya_kerja::class, 'opsi_id', 'opsi_id');
    }
}
