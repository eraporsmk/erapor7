<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasCompositePrimaryKey;

class Mata_pelajaran_kurikulum extends Model
{
    use HasFactory, HasCompositePrimaryKey;
    public $incrementing = false;
	protected $table = 'ref.mata_pelajaran_kurikulum';
	protected $primaryKey = ['kurikulum_id', 'mata_pelajaran_id', 'tingkat_pendidikan_id'];
	protected $guarded = [];
}
