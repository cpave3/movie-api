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
      Route::post('/genres', 'API_GenreController@store')->name('api.genres.create');
      Route::get('/genres', 'API_GenreController@list')->name('api.genres.list');
      Route::get('/genres/{genre_id}', 'API_GenreController@show')->name('api.genres.show');
      Route::put('/genres/{genre_id}', 'API_GenreController@update')->name('api.genres.update');
      Route::delete('/genres/{genre_id}', 'API_GenreController@delete')->name('api.genres.delete');
  });

});
