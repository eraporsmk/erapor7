<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.pekerjaan';
	protected $primaryKey = 'pekerjaan_id';
	protected $guarded = [];
}
