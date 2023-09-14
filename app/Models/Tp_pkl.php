<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Tp_pkl extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	protected $table = 'tp_pkl';
	protected $primaryKey = 'tp_pkl_id';
	protected $guarded = [];
	public function tp()
	{
		return $this->belongsTo(Tujuan_pembelajaran::class, 'tp_id', 'tp_id');
	}
	public function praktik_kerja_lapangan()
	{
		return $this->belongsTo(Praktik_kerja_lapangan::class, 'pkl_id', 'pkl_id');
	}
}
