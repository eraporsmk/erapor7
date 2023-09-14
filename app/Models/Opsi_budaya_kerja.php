<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opsi_budaya_kerja extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'ref.opsi_budaya_kerja';
	protected $primaryKey = 'opsi_id';
	protected $guarded = [];
}
