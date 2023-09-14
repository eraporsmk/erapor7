<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Teknik_penilaian extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'teknik_penilaian';
	protected $primaryKey = 'teknik_penilaian_id';
	protected $guarded = [];
}
