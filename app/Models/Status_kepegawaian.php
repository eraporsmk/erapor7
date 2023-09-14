<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status_kepegawaian extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'ref.status_kepegawaian';
	protected $primaryKey = 'status_kepegawaian_id';
	protected $guarded = [];
}
