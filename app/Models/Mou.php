<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Mou extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
    protected $table = 'mou';
    protected $primaryKey = 'mou_id';
	protected $guarded = [];
	
    public function dudi()
    {
        return $this->belongsTo(Dudi::class, 'dudi_id', 'dudi_id');
    }
    public function akt_pd()
    {
        return $this->hasMany(Akt_pd::class, 'mou_id', 'mou_id');
    }
    public function getTanggalMulaiAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y');
	}
    public function getTanggalSelesaiAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
	}
    
}
