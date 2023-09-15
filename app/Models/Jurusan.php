<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'ref.jurusan';
	protected $primaryKey = 'jurusan_id';
	protected $guarded = [];
	public function parent()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_induk', 'jurusan_id');
    }
    public function parrentRecursive()
    {
        return $this->parent()->with('parrentRecursive');
    }
}
