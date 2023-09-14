<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_remedial extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_remedial';
	protected $primaryKey = 'nilai_remedial_id';
	protected $guarded = [];
}
