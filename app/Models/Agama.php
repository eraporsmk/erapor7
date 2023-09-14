<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.agama';
	protected $primaryKey = 'agama_id';
    protected $guarded = [];
}
