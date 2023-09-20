<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Tp_mapel extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'tp_mapel';
	protected $primaryKey = 'tp_mapel_id';
	protected $guarded = [];
	public function tp()
	{
		return $this->belongsTo(Tujuan_pembelajaran::class, 'tp_id', 'tp_id');
	}
}
