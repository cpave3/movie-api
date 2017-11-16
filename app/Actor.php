<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Actor extends Model
{
    protected $fillable = [
      'name',
      'date_of_birth',
      'bio'
    ];

    protected $dates = [
      'date_of_birth'
    ];

    public function movies() {
      return $this->belongsToMany('App\Movie')->withPivot('character');
    }

    public function images() {
      return $this->morphMany('App\Image', 'imageable');
    }

    public function getAgeAttribute() {
      //calculate current age from date_of_birth
      return Carbon::now()->diffInYears($this->date_of_birth);
    }
}
