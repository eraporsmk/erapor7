<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_ukk extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'nilai_ukk';
	protected $primaryKey = 'nilai_ukk_id';
	protected $guarded = [];
    public function rencana_ukk(){
		return $this->hasOne(Rencana_ukk::class, 'rencana_ukk_id', 'rencana_ukk_id');
	}
}
