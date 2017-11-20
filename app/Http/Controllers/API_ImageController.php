<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Actor;
use App\Movie;
use App\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;

class API_ImageController extends Controller
{
    public function store(Request $request, $id) {
      $route = $request->route()->getName();
      $content = $request->instance();
      $json = $content->json()->all();
      $res = [];

      $rules = [
        "file_name" => "string|required|regex:/.*\.[a-zA-Z]+/",
        "raw_data" => "required"
      ];

      $validator = Validator::make($json, $rules);
      if ($validator->fails()) {
        //Pass errors to client
        return response()->json(['errors'=>$validator->errors()], 400);
      }

      if (explode(".", $route)[1] == "actors") {
        // Working with Actors
        $host = Actor::findOrFail($id);
      } elseif (explode(".", $route)[1] == "movies") {
        // Working with Movies
        $host = Movie::findOrFail($id);
      } else {
        // Exceptions
        return response()->json([], 400);
      }
      // Unified Logic

      //Make sure there is a file extension and the base64 is valid
      if (!preg_match("/.*\.[a-zA-Z]+/", $json['file_name']) || base64_encode(base64_decode($json['raw_data'], true)) === $json['raw_data']) {
        return response()->json([], 400);
      }

      $base64_str = substr($json['raw_data'], strpos($json['raw_data'], ",")+1);
      $image = base64_decode($base64_str);
      $filename = explode('.', $json['file_name'])[0]."-".time().".".explode('.', $json['file_name'])[1];
      Storage::disk('public_images')->put($filename, $image);
      $image = new Image();
      $image->filename = $filename;
      $host->images()->save($image);

      return response()->json([
        "id" => $image->id,
        "url" => $image->url,
        "mime" => $image->mime,
        "size" => $image->size,
      ],201)->header("Location", route("api.".explode(".", $route)[1].".images.show", [$host->id, $image->id]));
    }

    public function list(Request $request, $id) {
      $route = $request->route()->getName();
      $res = [];
      if (explode(".", $route)[1] == "actors") {
        // Working with Actors
        $host = Actor::findOrFail($id);
      } elseif (explode(".", $route)[1] == "movies") {
        // Working with Movies
        $host = Movie::findOrFail($id);
      } else {
        // Exceptions
        return response()->json([], 400);
      }
      // Unified Logic

      foreach ($host->images as $image) {
        $res[] = [
          "id" => $image->id,
          "url" => $image->url,
          "mime" => $image->mime,
          "size" => $image->size,
        ];
      }


      return response()->json($res,200);


    }

    public function delete(Request $request, $id, $image_id) {
      $route = $request->route()->getName();
      $res = [];
      if (explode(".", $route)[1] == "actors") {
        // Working with Actors
        $host = Actor::findOrFail($id);
      } elseif (explode(".", $route)[1] == "movies") {
        // Working with Movies
        $host = Movie::findOrFail($id);
      } else {
        // Exceptions
        return response()->json([], 400);
      }
      // Unified Logic

      $image = Image::findOrFail($image_id);
      Storage::disk('public_images')->delete($image->filename);
      $image->delete();
      return response(null, 204);
    }

    public function show(Request $request, $id, $image_id) {
      $route = $request->route()->getName();
      $res = [];
      if (explode(".", $route)[1] == "actors") {
        // Working with Actors
        $host = Actor::findOrFail($id);
      } elseif (explode(".", $route)[1] == "movies") {
        // Working with Movies
        $host = Movie::findOrFail($id);
      } else {
        // Exceptions
        return response()->json([], 400);
      }
      // Unified Logic
      $image = Image::findOrFail($image_id);

        $res = [
          "id" => $image->id,
          "url" => $image->url,
          "mime" => $image->mime,
          "size" => $image->size,
        ];


      return response()->json($res,200);
    }
}
