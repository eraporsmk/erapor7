<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggota_akt_pd extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'anggota_akt_pd';
	protected $primaryKey = 'anggota_akt_pd_id';
	protected $guarded = [];
	
    public function siswa()
    {
        return $this->belongsTo(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
    }
    public function anggota_rombel()
    {
        return $this->belongsTo(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
    }
    
    public function akt_pd()
    {
        return $this->belongsTo(Akt_pd::class, 'akt_pd_id', 'akt_pd_id');
    }
}
