<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     public function test_it_can_create_a_genre() {
       $data = ["name" => "new genre"];
       $this->json('POST', route('api.genres.create'), $data)
            ->assertStatus(201)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name"
              ]
            ]);
     }

     public function test_it_rejects_malformed_create_requests() {
       $data = ["invalid_key" => "new genre"];
       $this->json('POST', route('api.genres.create'), $data)
            ->assertStatus(400);
     }

     public function test_it_fetches_all_genres() {
       $this->get(route('api.genres.list'))
            ->assertStatus(200)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name",
                "movies" => [
                  "*" => [
                    "id",
                    "name",
                    "year",
                    "rating",
                    "description",
                    "actors" => [
                      "*" => [
                        "id",
                        "name",
                        "bio",
                        "character",
                        "dob"
                      ]
                    ]
                  ]
                  ]
              ]
            ]);
     }

     public function test_it_returns_single_genres() {
       $this->json('GET', route('api.genres.show', 1))
            ->assertStatus(200)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name",
                "movies" => [
                  "*" => [
                    "id",
                    "name",
                    "year",
                    "rating",
                    "description",
                    "actors" => [
                      "*" => [
                        "id",
                        "name",
                        "bio",
                        "character",
                        "dob"
                      ]
                    ]
                  ]
                  ]
              ]
            ]);;
     }

     public function test_it_fails_finding_fake_ids() {
       $this->json('GET', route('api.genres.show', 100))
            ->assertStatus(404);
     }

     public function test_it_can_update_a_genre() {
       $data = [
         "name" => "Action Movies",
         "moviesSync" => [1,2,4,5,6],
         "moviesAttach" => [3],
         "moviesDetach" => [5,6]
       ];
       $this->json('PUT', route('api.genres.update', 1), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
              "*" => [
                "id",
                "name",
                "movies" => [
                  "*" => [
                    "id",
                    "name",
                    "year",
                    "rating",
                    "description",
                    "actors" => [
                      "*" => [
                        "id",
                        "name",
                        "bio",
                        "character",
                        "dob"
                      ]
                    ]
                  ]
                ]
              ]
            ]);
            // ->seeJsonEquals(["name" => $data['name']]);
     }

     public function test_it_can_delete_genres() {
       $this->json('DELETE', route('api.genres.delete', 1))
            ->assertStatus(204);
     }

     public function test_handles_improper_deletes() {
       $this->json('DELETE', route('api.genres.delete', 100))
            ->assertStatus(404);
     }
}
