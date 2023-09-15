<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_pkl extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_pkl';
	protected $primaryKey = 'nilai_pkl_id';
	protected $guarded = [];
    public function praktik_kerja_lapangan()
	{
		return $this->belongsTo(Praktik_kerja_lapangan::class, 'pkl_id', 'pkl_id');
	}
    public function tp_pkl()
	{
		return $this->belongsTo(Tp_pkl::class, 'tp_id', 'tp_id');
	}
	public function tp()
	{
		return $this->belongsTo(Tujuan_pembelajaran::class, 'tp_id', 'tp_id');
	}
}
