<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
  protected $fillable = [
    'name',
    'rating',
    'description'
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
}
