<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_us extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	protected $table = 'nilai_us';
	protected $primaryKey = 'nilai_us_id';
	protected $guarded = [];
}
