<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Genre;
use App\Movie;
use App\Actor;

use Carbon\Carbon;

class API_GenreController extends Controller
{
    public function store(Request $request) {
      $content = $request->instance();
      $json = $content->json()->all();
      $res = [];

      if (isset($json['name'])) {
        // Mandatory fields are valid
        $genre = Genre::create($json);
        $res[] = [
          "id" => $genre->id,
          "name" => $genre->name
        ];
        return response()->json($res, 201);
      } else {
        //Bad Request
        return response()->json([], 400);
      }

    }

    public function list() {
      $genres = Genre::all();
      $res = [];

      foreach ($genres as $genre) {
        foreach ($genre->movies as $movie) {
          foreach ($movie->actors as $actor) {
            $actor->character = $actor->pivot->character;
            $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');
            unset($actor->date_of_birth);
          }
        }

        $res[] = [
          "id" => $genre->id,
          "name" => $genre->name,
          "movies" => $genre->movies
        ];
      }

      return response()->json($res, 200);
    }

    public function show($genre_id) {
      $genre = Genre::findOrFail($genre_id);
      foreach ($genre->movies as $movie) {
        foreach ($movie->actors as $actor) {
          $actor->character = $actor->pivot->character;
          $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');
          unset($actor->date_of_birth);
        }

      }
      $res[] = [
        "id" => $genre->id,
        "name" => $genre->name,
        "movies" => $genre->movies
      ];

      return response()->json($res, 200);
    }

    public function update(Request $request, $genre_id) {
      $genre = Genre::findOrFail($genre_id);
      $content = $request->instance();
      $json = $content->json()->all();

      $genre->name = $json['name'];
      $genre->save();

      //Handle Synchronisation
      if (isset($json['moviesSync'])) {
        $genre->movies()->sync($json['moviesSync']);
      }
      if (isset($json['moviesAttach'])) {
        $genre->movies()->attach($json['moviesAttach']);
      }
      if (isset($json['moviesDetach'])) {
        $genre->movies()->detach($json['moviesDetach']);
      }

      foreach ($genre->movies as $movie) {
        foreach ($movie->actors as $actor) {
          $actor->character = $actor->pivot->character;
          $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');
          unset($actor->date_of_birth);
        }

      }
      $res[] = [
        "id" => $genre->id,
        "name" => $genre->name,
        "movies" => $genre->movies
      ];

      return response()->json($res, 200);
    }

    public function delete($genre_id) {
      $genre = Genre::findOrFail($genre_id);
      $genre->movies()->sync([]);
      $genre->delete();

      return response(null, 204);

    }
}
