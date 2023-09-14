<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    /*
    protected $table = 'settings';
    protected $primaryKey = 'key';
    protected $fillable = ['key', 'value'];
    public function getKeyAttribute($value)
    {
        return $value;
    }
    public function get($query, $key)
    {
        $key = $query->where('key', '=', $key)->first();
        return $key->value;
    }
	static function scopeOfType($query, $key)
    {
        $key = $query->where('key', $key)->first();
		return $key->value;
    }
    */
}
