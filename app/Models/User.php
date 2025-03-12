<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use App\Traits\Uuid;
use Carbon\Carbon;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Uuid;
    public $incrementing = false;
    public $keyType = 'string';
	protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];
    protected $appends = ['login_terakhir'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
    }
    public function pd()
    {
        return $this->hasOne(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
    }
    public function sekolah()
    {
        return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }
    public function getLastLoginAtAttribute($date)
    {
        return ($date) ? Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y H:i:s') : '';
    }
    /*public function getLoginTerakhirAttribute()
	{
        if($this->attributes['last_login_at']){
            return Carbon::parse($this->attributes['last_login_at'])->translatedFormat('d F Y').' Pukul '.Carbon::parse($this->attributes['last_login_at'])->format('H:i:s');
        } else {
            return '-';
        }
	}*/
    public function access_token()
    {
        return $this->hasOne(AccessToken::class, 'tokenable_id');
    }
    public function getLoginTerakhirAttribute()
	{
        return ($this->access_token) ? Carbon::parse($this->access_token->last_used_at)->format('d/m/Y H:i:s') : NULL;
	}
}
