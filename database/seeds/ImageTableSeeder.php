<?php

use Illuminate\Database\Seeder;

use App\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('images')->delete();
      $json = File::get("database/data/images.json");
      $data = json_decode($json);
      foreach ($data as $object) {

        $base64_str = substr($object->raw_data, strpos($object->raw_data, ",")+1);
        $image = base64_decode($base64_str);
        $filename = explode('.', $object->file_name)[0].".".explode('.', $object->file_name)[1];
        Storage::disk('public_images')->put($filename, $image);

        $image = Image::create([
          'filename' => $filename,
          'imageable_type' => $object->imageable_type,
          'imageable_id' => $object->imageable_id,
        ]);
      }
    }
}
