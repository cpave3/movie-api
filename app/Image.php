<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
      'filename',
      'mime',
      'original_filename',
    ];

    public function imageable() {
      return $this->morphTo();
    }
}
