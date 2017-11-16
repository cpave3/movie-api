<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{

    protected $fillable = [
      'name'
    ];

    public function movies() {
      return $this->hasMany('App\Movie');
    }

    public function actors() {
      return $this->hasManyThrough('App\Actor', 'App\Movie');
    }

}
