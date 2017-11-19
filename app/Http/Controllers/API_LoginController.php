<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class API_LoginController extends Controller
{
    public function login(Request $request) {
      $content = $request->instance();
      $json = $content->json()->all();

      //Take the values passed from the json request and attempt Authentication
      if (Auth::attempt(['email' => $json['email'], 'password' => $json['password']])) {
           $user = Auth::user();
           $response = [];
           $response['name'] = $user->name;
           $response['email'] = $user->email;
           $response['keys'] = [];
           foreach ($user->apiKeys as $apiKey) {
             $response['keys'][] = $apiKey->key;
           }
          return response()->json($response, 200);
       }
    }
}
