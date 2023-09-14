<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kompetensi_dasar extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'ref.kompetensi_dasar';
	protected $primaryKey = 'kompetensi_dasar_id';
	protected $guarded = [];
	public function mata_pelajaran(){
		return $this->hasOne(Mata_pelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
}
