<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_pts extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_pts';
	protected $primaryKey = 'nilai_pts_id';
	protected $guarded = [];
    public function rapor_pts()
    {
        return $this->belongsTo(Rapor_pts::class, 'rapor_pts_id', 'rapor_pts_id');
    }
    
}
