<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected function transform($key, $value)
    {
        // Abort on "nested json data"
        foreach ($this->except as $k) {
            if (str_ends_with($k, '.*') && str_starts_with($key, substr($k, 0, -2))) {
                return $value;
            }
        }

        return parent::transform($key, $value);
    }
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
        'kode_wilayah',
        'wilayah_kcd.*',
        'provinsi_id',
        'kabupaten_id',
    ];
}
