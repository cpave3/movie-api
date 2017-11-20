<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
  protected $fillable = [
    'name',
    'year',
    'rating',
    'description'
  ];

  protected $hidden = [
    'pivot',
    'created_at',
    'updated_at'
  ];

  public function genres() {
    return $this->belongsToMany('App\Genre');
  }

  public function actors() {
    return $this->belongsToMany('App\Actor')->withPivot('character');
  }

  public function images() {
    return $this->morphMany('App\Image', 'imageable');
  }

  public function users() {
    return $this->belongsToMany('App\User');
  }

}
