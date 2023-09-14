<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Penilaian_ukk extends Model
{
    use HasFactory,SoftDeletes;
    public $incrementing = false;
	protected $table = 'penilaian_ukk';
	protected $primaryKey = 'penilaian_ukk_id';
	protected $guarded = [];
}
