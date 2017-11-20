<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Chrisbjr\ApiGuard\Models\Mixins\Apikeyable;

class User extends Authenticatable
{
    use Notifiable;
    use Apikeyable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function movies() {
      return $this->belongsToMany('App\Movie');
    }

}
