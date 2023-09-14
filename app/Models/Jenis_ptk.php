<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_ptk extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.jenis_ptk';
	protected $primaryKey = 'jenis_ptk_id';
	protected $guarded = [];
}
