<?php

namespace App\Models;
/* Imports */
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
use HasRoles;
        use HasFactory;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;


    protected $fillable = [
        'name',
        'email',
        'profile_photo_path',
        'current_team_id',
    
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var  array
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
            ];

    protected $dates = [
            'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'api_route',
        'can',
        'profile_photo_url',
    ];

    /* ************************ ACCESSOR ************************* */

    public function getApiRouteAttribute() {
        return route("api.users.index");
    }
    public function getCanAttribute() {
        return [
            "view" => \Auth::check() && \Auth::user()->can("view", $this),
            "update" => \Auth::check() && \Auth::user()->can("update", $this),
            "delete" => \Auth::check() && \Auth::user()->can("delete", $this),
            "restore" => \Auth::check() && \Auth::user()->can("restore", $this),
            "forceDelete" => \Auth::check() && \Auth::user()->can("forceDelete", $this),
        ];
    }

    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    /* ************************ RELATIONS ************************ */
}
