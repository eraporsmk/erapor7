<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Nilai_karakter extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'nilai_karakter';
	protected $primaryKey = 'nilai_karakter_id';
	protected $guarded = [];
	public function sikap(){
		return $this->hasOne(Sikap::class, 'sikap_id', 'sikap_id');
	}
}
