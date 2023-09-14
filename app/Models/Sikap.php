<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    use HasFactory;
    protected $table = 'ref.sikap';
	protected $primaryKey = 'sikap_id';
	protected $guarded = [];
	public function sikap(){
		return $this->hasMany(Sikap::class, 'sikap_induk', 'sikap_id');
	}
	public function nilai_sikap()
	{
		return $this->hasMany(Nilai_sikap::class, 'sikap_id', 'sikap_id');
	}
}
