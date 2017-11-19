<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Actor;
use App\Genre;
use App\Movie;

use Carbon\Carbon;

class API_MovieController extends Controller
{
    public function list() {
      $movies = Movie::all();
      $res = [];

      foreach ($movies as $movie) {
        // foreach ($movie->genres as $genre) {
        //   $movie->character = $movie->pivot->character;
        //
        // }
        foreach ($movie->actors as $actor) {
          $actor->character = $actor->pivot->character;
          $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');
          $actor->images = $actor->images;
          // unset($movie->date_of_birth);
        }

        $res[] = [
          "id" => $movie->id,
          "name" => $movie->name,
          "year" => $movie->year,
          "rating" => $movie->rating,
          "description" => $movie->description,
          "images" => $movie->images,
          "genres" => $movie->genres,
          "actors" => $movie->actors
        ];
      }

      return response()->json($res, 200);
    }

    public function store(Request $request) {
      $content = $request->instance();
      $json = $content->json()->all();
      $res = [];

      if (isset($json['name']) && isset($json['year']) && isset($json['rating']) && isset($json['description'])) {
        // Mandatory fields are valid
        $movie = Movie::create($json);


        if (isset($json['genres'])) {
          // Associate genres

          foreach ($json['genres'] as $genre) {
            $movie->genres()->attach($genre);
          }
        }

        if (isset($json['actors'])) {
          // Associate Actors
          foreach ($json['actors'] as $actor) {
            $movie->actors()->attach($actor['id'], ["character" => $actor['character']]);
          }
          foreach ($movie->actors as $actor) {
            $actor->character = $actor->pivot->character;
            $actor->dob = $actor->date_of_birth->format('d/m/Y');
            $actor->images = $actor->images;
          }
        }

        if (isset($json['moviesAttach'])) {
          $attach = [];
          foreach ($json['moviesAttach'] as $s) {
            $attach[$s['id']] = ["character" => $s['character']];
          }
          $actor->movies()->attach($attach);
        }

        $res[] = [
          "id" => $movie->id,
          "name" => $movie->name,
          "year" => $movie->year,
          "rating" => $movie->rating,
          "description" => $movie->description,
          "images" => $movie->images,
          "genres" => $movie->genres,
          "actors" => $movie->actors
        ];

        return response()->json($res, 201)->header("Location", route("api.movies.show", $movie->id));
      } else {
        //Bad Request
        return response()->json([], 400);
      }
    }

    public function show($movie_id) {
      $movie = Movie::findOrFail($movie_id);
      $res = [];

        foreach ($movie->actors as $actor) {
          $actor->character = $actor->pivot->character;
          $actor->dob = Carbon::createFromFormat('Y-m-d H:i:s', $actor->date_of_birth)->format('d/m/Y');
          $actor->images = $actor->images;
        }

        $res[] = [
          "id" => $movie->id,
          "name" => $movie->name,
          "year" => $movie->year,
          "rating" => $movie->rating,
          "description" => $movie->description,
          "images" => $movie->images,
          "genres" => $movie->genres,
          "actors" => $movie->actors
        ];

      return response()->json($res, 200);
    }

    public function update(Request $request, $movie_id) {

      $movie = Movie::findOrFail($movie_id);
      $content = $request->instance();
      $json = $content->json()->all();

      if (isset($json['name']) || isset($json['year']) || isset($json['rating']) || isset($json['description'])) {
        $movie->update($json);
      }

      //Handle Synchronisation
      if (isset($json['actorsSync'])) {
        $sync = [];
        foreach ($json['actorsSync'] as $s) {
          $sync[$s['id']] = ["character" => $s['character']];
        }
        $movie->actors()->sync($sync);
      }

      if (isset($json['actorsAttach'])) {
        $attach = [];
        foreach ($json['actorsAttach'] as $s) {
          $attach[$s['id']] = ["character" => $s['character']];
        }
        $movie->actors()->attach($attach);
      }

      if (isset($json['actorsDetach'])) {
        $movie->actors()->detach($json['actorsDetach']);
      }

      // Genre Sync

      if (isset($json['genresSync'])) {
        $movie->genres()->sync($json['genresSync']);
      }

      if (isset($json['genresAttach'])) {
        $movie->genres()->attach($json['genresAttach']);
      }

      if (isset($json['genresDetach'])) {
        $movie->genres()->detach($json['genresDetach']);
      }

      foreach ($movie->actors as $actor) {
          $actor->character = $actor->pivot->character;
          $actor->dob = $actor->date_of_birth->format('d/m/Y');
          $actor->images = $actor->images;
      }

      $res[] = [
        "id" => $movie->id,
        "name" => $movie->name,
        "year" => $movie->year,
        "rating" => $movie->rating,
        "description" => $movie->description,
        "images" => $movie->images,
        "genres" => $movie->genres,
        "actors" => $movie->actors
      ];

      return response()->json($res, 200)->header("Location", route("api.movies.show", $movie->id));

    }

    public function delete($movie_id) {
      $movie = Movie::findOrFail($movie_id);
      $movie->genres()->sync([]);
      $movie->actors()->sync([]);
      $movie->delete();
      return response(null, 204);
    }
}
