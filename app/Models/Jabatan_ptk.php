<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_ptk extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.jabatan_ptk';
	protected $primaryKey = 'jabatan_ptk_id';
	protected $guarded = [];
}
