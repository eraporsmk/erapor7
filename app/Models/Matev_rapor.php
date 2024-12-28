<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Matev_rapor extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $table = 'dapodik.matev_rapor';
	protected $primaryKey = 'id_evaluasi';
    protected $guarded = [];

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'last_update';

    public function pembelajaran()
    {
        return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
    }
    public function rombongan_belajar()
    {
        return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
    }
}
