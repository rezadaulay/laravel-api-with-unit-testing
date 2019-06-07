<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function validateForPassportPasswordGrant($password) {
        //check for password
        if (Hash::check($password, $this->getAuthPassword())) { 
            if ($this->active) { 
                return true;
            } else{
                throw new OAuthServerException('User has been inactivated.', 6, 'account_inactive', 401);
           }
        }
    }
}
