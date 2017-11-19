<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Image extends Model
{
    protected $fillable = [
      'filename',
      'mime',
      'original_filename',
    ];

    protected $appends = [
      'url',
      'mime',
      'size'
    ];

    protected $hidden = [
      'created_at',
      'updated_at'
    ];

    public function imageable() {
      return $this->morphTo();
    }

    public function getUrlAttribute() {
      return Storage::disk('public_images')->url($this->filename);
    }

    public function getMimeAttribute() {
      return Storage::disk('public_images')->mimeType($this->filename);
    }

    public function getSizeAttribute() {
      return Storage::disk('public_images')->size($this->filename);
    }
}
