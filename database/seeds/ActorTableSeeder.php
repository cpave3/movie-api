<?php

use Illuminate\Database\Seeder;

use App\Actor;
use Carbon\Carbon;

class ActorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('actors')->delete();
      $json = File::get("database/data/actors.json");
      $data = json_decode($json);
      foreach ($data as $object) {
        $actor = Actor::create([
          'id' => $object->id,
          'name' => $object->name,
          'date_of_birth' => Carbon::createFromFormat('!d/m/Y', $object->dob)->format('Y-m-d H:i:s'),
          'bio' => $object->bio
        ]);
        foreach ($object->movies as $m) {
          $actor->movies()->attach($m, ["character" => $object->characters]);
        }
      }
    }
}
