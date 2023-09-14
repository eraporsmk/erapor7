<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mata_pelajaran extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.mata_pelajaran';
	protected $primaryKey = 'mata_pelajaran_id';
	protected $guarded = [];
	
	public function pembelajaran()
	{
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	
	public function mata_pelajaran_kurikulum()
	{
		return $this->hasMany(Mata_pelajaran_kurikulum::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function kompetensi_dasar()
	{
		return $this->hasMany(Kompetensi_dasar::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
}
