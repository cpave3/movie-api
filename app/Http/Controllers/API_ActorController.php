<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Actor;
use App\Genre;
use App\Movie;

use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;

class API_ActorController extends Controller
{
    public function list() {
      $actors = Actor::all();
      $res = [];

      foreach ($actors as $actor) {
        foreach ($actor->movies as $movie) {
          $movie->character = $movie->pivot->character;
          foreach ($movie->genres as $genre) {
            // $actor->character = $actor->pivot->character;
            $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');

          }
        }

        $res[] = [
          "id" => $actor->id,
          "name" => $actor->name,
          "dob" => $actor->dob,
          "age" => $actor->age,
          "bio" => $actor->bio,
          "movies" => $actor->movies
        ];
      }

      return response()->json($res, 200);
    }

    public function store(Request $request) {

      $content = $request->instance();
      $json = $content->json()->all();
      $res = [];

      $rules = [
        "name" => "required",
        "dob" => "date_format:d/m/Y",
        "bio" => "required",
        "movies" => "array|nullable"
      ];
      $validator = Validator::make($json, $rules);
      if ($validator->fails()) {
        //Pass errors to client
        return response()->json(['errors'=>$validator->errors()], 400);
      }

      if (isset($json['name']) && isset($json['dob']) && isset($json['bio'])) {
        // Mandatory fields are valid
        $json['date_of_birth'] = Carbon::createFromFormat('!d/m/Y', $json['dob'])->format('Y-m-d H:i:s');
        $actor = Actor::create($json);

        if (isset($json['movies'])) {
          $attach = [];
          foreach ($json['movies'] as $s) {
            $attach[$s['id']] = ["character" => $s['character']];
          }
          $actor->movies()->attach($attach);
        }

        foreach ($actor->movies as $movie) {
          $movie->character = $movie->pivot->character;
        }

        $res = [
          "id" => $actor->id,
          "name" => $actor->name,
          "dob" => Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y'),
          "bio" => $actor->bio,
          "age" => $actor->age,
          "movies" => $actor->movies
        ];

        return response()->json($res, 201)->header("Location", route("api.actors.show", $actor->id));
      } else {
        //Bad Request
        return response()->json([], 400);
      }

    }

    public function show($actor_id) {
      $actor = Actor::findOrFail($actor_id);
      $res = [];

        foreach ($actor->movies as $movie) {
          $movie->character = $movie->pivot->character;
          foreach ($movie->genres as $genre) {
            // $actor->character = $actor->pivot->character;
            $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');

          }
        }

        $res = [
          "id" => $actor->id,
          "name" => $actor->name,
          "dob" => $actor->dob,
          "age" => $actor->age,
          "bio" => $actor->bio,
          "movies" => $actor->movies
        ];


      return response()->json($res, 200);
    }

    public function update(Request $request, $actor_id) {

      $actor = Actor::findOrFail($actor_id);
      $content = $request->instance();
      $json = $content->json()->all();

      $rules = [
        "name" => "string|nullable",
        "dob" => "date_format:d/m/Y|nullable",
        "bio" => "string|nullable",
        "movies" => "array|nullable"
      ];
      $validator = Validator::make($json, $rules);
      if ($validator->fails()) {
        //Pass errors to client
        return response()->json(['errors'=>$validator->errors()], 400);
      }

      if (isset($json['dob'])) {
        $json['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $json['dob'])->format('Y-m-d H:i:s');
      }

      $actor->update($json);

      //Handle Synchronisation
      if (isset($json['moviesSync'])) {
        $sync = [];
        foreach ($json['moviesSync'] as $s) {
          $sync[$s['id']] = ["character" => $s['character']];
        }
        $actor->movies()->sync($sync);
      }

      if (isset($json['moviesAttach'])) {
        $attach = [];
        foreach ($json['moviesAttach'] as $s) {
          $attach[$s['id']] = ["character" => $s['character']];
        }
        $actor->movies()->attach($attach);
      }

      if (isset($json['moviesDetach'])) {
        $actor->movies()->detach($json['moviesDetach']);
      }

      foreach ($actor->movies as $movie) {
          $movie->character = $movie->pivot->character;
      }

      $res[] = [
        "id" => $actor->id,
        "name" => $actor->name,
        "age" => $actor->age,
        "dob" => $actor->date_of_birth->format('d/m/Y'),
        "bio" => $actor->bio,
        "movies" => $actor->movies
      ];

      return response()->json($res, 200)->header("Location", route("api.actors.show", $actor->id));

    }

    public function delete($actor_id) {
      $actor = Actor::findOrFail($actor_id);
      $actor->movies()->sync([]);
      $actor->delete();
      return response(null, 204);
    }
}
