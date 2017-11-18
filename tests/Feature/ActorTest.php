<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_list_all_actors() {
      $this->json("GET", route('api.actors.list'))
            ->assertStatus(200)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name",
                "dob",
                "age",
                "bio",
                "movies" => [
                  "*" => [
                    "id",
                    "character"
                  ]
                ]
              ]
            ]);
    }

    public function test_it_creates_new_actors() {
      $data = [
        "name" => "Tom Holland",
        "dob" => "01/06/1996",
        "bio" => "Bio info",
        "movies" => [
          [
            "id" => 13,
            "character" => "Peter Parker"
          ]
        ]
      ];

      $this->json('POST', route('api.actors.store'), $data)
           ->assertStatus(201)
           ->assertJsonStructure([
             "*" => [
               "id",
               "name",
               "dob",
               "bio",
               "movies" => [
                 "*" => [
                   "id",
                   "character"
                 ]
               ]
             ]
           ]);
    }

    public function test_it_rejects_malformed_create_requests() {
      $data = ["invalid_key" => "new genre"];
      $this->json('POST', route('api.actors.store'), $data)
           ->assertStatus(400);
    }

    public function test_it_returns_single_actors() {
      $this->json("GET", route('api.actors.show', 1))
            ->assertStatus(200)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name",
                "dob",
                "age",
                "bio",
                "movies" => [
                  "*" => [
                    "id",
                    "character"
                  ]
                ]
              ]
            ]);
    }

    public function test_it_handles_non_existent_actors() {
      $this->json("GET", route('api.actors.show', 1000000))
            ->assertStatus(404);
    }

    public function test_it_deletes_actors() {
      $this->json('DELETE', route('api.actors.delete', 5))
           ->assertStatus(204);
    }

    public function test_it_cant_delete_non_existent_actors() {
      $this->json('DELETE', route('api.actors.delete', 500))
           ->assertStatus(404);
    }

    public function test_it_can_update_an_actor() {
      $data = [
        "name" => "Test Name",
        "dob" => "01/01/2001",
        "bio" => "updated",
        "moviesSync" => [
          ["id" => 1, "character" => "Tony"]
        ],
        "moviesAttach" => [
          ["id" => 1, "character" => "Tony"]
        ],
        "moviesDetach" => [5,6]
      ];
      $this->json('PUT', route('api.actors.update', 1), $data)
           ->assertStatus(200)
           ->assertJsonStructure([
             "*" => [
               "id",
               "name",
               "age",
               "bio",
               "dob",
               "movies" => [
                 "*" => [
                   "id",
                   "name",
                   "year",
                   "rating",
                   "description",
                   "character"
                 ]
               ]
             ]
           ]);
    }
}
