<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.semester';
	protected $primaryKey = 'semester_id';
	protected $guarded = [];
	
	public function tahun_ajaran()
	{
		return $this->hasOne(Tahun_ajaran::class, 'tahun_ajaran_id', 'tahun_ajaran_id');
	}
	public function anggota_rombel()
	{
		return $this->hasOne(Anggota_rombel::class, 'semester_id', 'semester_id');
	}
}
