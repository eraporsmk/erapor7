<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Tujuan_pembelajaran extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'tujuan_pembelajaran';
	protected $primaryKey = 'tp_id';
	protected $guarded = [];
	
    public function cp()
    {
        return $this->belongsTo(Capaian_pembelajaran::class, 'cp_id', 'cp_id');
    }
    public function kd()
    {
        return $this->belongsTo(Kompetensi_dasar::class, 'kd_id', 'kompetensi_dasar_id');
    }
    public function tp_mapel()
    {
        return $this->hasManyThrough(
            Pembelajaran::class,
            Tp_mapel::class,
            'tp_id', // Foreign key on history table...
            'pembelajaran_id', // Foreign key on users table...
            'tp_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        );
        return $this->hasMany(Tp_mapel::class, 'tp_id', 'tp_id');
    }
}
