<?php

use Illuminate\Database\Seeder;

use App\Genre;

class GenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('genres')->delete();
      $json = File::get("database/data/genres.json");
      $data = json_decode($json);
      foreach ($data as $object) {
        Genre::create([
          'id' => $object->id,
          'name' => $object->name
        ]);
      }
    }
}
