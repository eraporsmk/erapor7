<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Status_penilaian extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	protected $table = 'status_penilaian';
	protected $primaryKey = 'status_penilaian_id';
	protected $guarded = [];
}
