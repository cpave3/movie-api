<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function() {

  //Non secure Api Routes
  Route::post('/login', 'API_LoginController@login')->name('api.login');

  // Secure Api Routes
  Route::group(['middleware' => 'auth.apikey'], function() {

      Route::get('/', function() {
        $routes = Route::getRoutes();
        $res = [];
        $res["documentaion"] = "https://app.apiary.io/movieapi62";
        $res["valid_endpoints"] = [];
        foreach ($routes as $route) {
          if ($route->action['prefix'] == "api/v1") {
            $res["valid_endpoints"][] = [$route->methods[0] => $route->uri];
          }
        }
        return response()->json($res, 200);
      })->name('api.index');

      // Genres
      Route::post('/genres', 'API_GenreController@store')->name('api.genres.store');
      Route::get('/genres', 'API_GenreController@list')->name('api.genres.list');
      Route::get('/genres/{genre_id}', 'API_GenreController@show')->name('api.genres.show');
      Route::put('/genres/{genre_id}', 'API_GenreController@update')->name('api.genres.update');
      Route::delete('/genres/{genre_id}', 'API_GenreController@delete')->name('api.genres.delete');

      // Actors
      Route::post('/actors', 'API_ActorController@store')->name('api.actors.store');
      Route::get('/actors', 'API_ActorController@list')->name('api.actors.list');
      Route::get('/actors/{actor_id}', 'API_ActorController@show')->name('api.actors.show');
      Route::put('/actors/{actor_id}', 'API_ActorController@update')->name('api.actors.update');
      Route::delete('/actors/{actor_id}', 'API_ActorController@delete')->name('api.actors.delete');
      // IMAGES
      Route::post('/actors/{actor_id}/images', 'API_ImageController@store')->name('api.actors.images.store');
      Route::get('/actors/{actor_id}/images', 'API_ImageController@list')->name('api.actors.images.list');
      Route::get('/actors/{actor_id}/images/{image_id}', 'API_ImageController@show')->name('api.actors.images.show');
      Route::delete('/actors/{actor_id}/images/{image_id}', 'API_ImageController@delete')->name('api.actors.images.delete');

      // Movies
      Route::post('/movies', 'API_MovieController@store')->name('api.movies.store');
      Route::get('/movies', 'API_MovieController@list')->name('api.movies.list');
      Route::get('/movies/{movie_id}', 'API_MovieController@show')->name('api.movies.show');
      Route::put('/movies/{movie_id}', 'API_MovieController@update')->name('api.movies.update');
      Route::delete('/movies/{movie_id}', 'API_MovieController@delete')->name('api.movies.delete');
      // IMAGES
      Route::post('/movies/{movie_id}/images', 'API_ImageController@store')->name('api.movies.images.store');
      Route::get('/movies/{movie_id}/images', 'API_ImageController@list')->name('api.movies.images.list');
      Route::get('/movies/{movie_id}/images/{image_id}', 'API_ImageController@show')->name('api.movies.images.show');
      Route::delete('/movies/{movie_id}/images/{image_id}', 'API_ImageController@delete')->name('api.movies.images.delete');



  });

});
