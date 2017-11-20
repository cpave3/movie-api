<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FaveTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_add_favourite() {
        $this->actingAs(User::findOrFail(1))
             ->json('POST', route('api.favourites.store', 1))
             ->assertStatus(201);
    }

    public function test_it_can_remove_faveourites() {
        $this->actingAs(User::findOrFail(1))
             ->json('POST', route('api.favourites.store', 1))
             ->assertStatus(201);

        $this->actingAs(User::findOrFail(1))
             ->json('DELETE', route('api.favourites.delete', 1))
             ->assertStatus(204);
    }

    public function test_it_returns_list() {
        $this->actingAs(User::findOrFail(1))
             ->json('POST', route('api.favourites.store', 1))
             ->assertStatus(201);

         $this->actingAs(User::findOrFail(1))
              ->json('GET', route('api.favourites.list'))
              ->assertStatus(200)
              ->assertJsonStructure([
                "*" => [
                  "id",
                  "name",
                  "year",
                  "rating",
                  "description",
                  "images" => [
                    "*" => [

                    ]
                  ],
                  "genres" => [
                    "*" => [
                      "id",
                      "name"
                    ]
                  ],
                  "actors" => [
                    "*" => [
                      "id",
                      "name",
                      "character",
                      "bio",
                      "age",
                      "dob",
                      "images" => [
                        "*" => [
                          "id",
                          "url",
                          "mime",
                          "size"
                        ]
                      ]
                    ]
                  ]
                ]
              ]);
    }
}
