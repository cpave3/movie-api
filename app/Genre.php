<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{

    protected $fillable = [
      'name'
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
      'pivot'
    ];

    public function movies() {
      return $this->belongsToMany('App\Movie');
    }

    public function actors() {
      return $this->hasManyThrough('App\Actor', 'App\Movie');
    }

}
