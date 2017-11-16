<?php

use Illuminate\Database\Seeder;
use App\Movie;

class MovieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies')->delete();
        $json = File::get("database/data/movies.json");
        $data = json_decode($json);
        foreach ($data as $object) {
          Movie::create([
            'id' => $object->id,
            'name' => $object->name,
            'rating' => $object->rating,
            'year' => $object->year,
            'description' => $object->description
          ]);
        }
    }
}
