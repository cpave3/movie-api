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

  public function genre() {
    return $this->belongsTo('App\Genre');
  }

  public function actors() {
    return $this->belongsToMany('App\Actor');
  }

  public function images() {
    return $this->morphMany('App\Image', 'imageable');
  }
}
