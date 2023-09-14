<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bimbing_pd extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'bimbing_pd';
	protected $primaryKey = 'bimbing_pd_id';
	protected $guarded = [];
	public function akt_pd(){
        return $this->belongsTo(Akt_pd::class, 'akt_pd_id', 'akt_pd_id');
		//return $this->hasOne('App\Akt_pd', 'akt_pd_id', 'akt_pd_id');
		/*return $this->hasManyThrough(
            'App\Akt_pd',
            'App\Mou',
            '1', // Foreign key on history table...
            'mou_id', // Foreign key on users table...
            '3', // Local key on suppliers table...
            'mou_id' // Local key on users table...
        );*/
	}
    public function guru()
    {
        return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
    }
}
