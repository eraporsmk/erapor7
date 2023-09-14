<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
    protected $table = 'ref.kelompok';
	protected $primaryKey = 'kelompok_id';
	protected $guarded = [];
}
