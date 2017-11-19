<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_list_movies() {
      $this->json("GET", route('api.movies.list'))
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

    public function test_it_creates_new_movies() {
      $data = [
        "name" => "Captain Marvel",
        "year" => "2019",
        "rating" => "0.0",
        "description" => "There is no description yet",
        "genres" => [1,4,5],
        "actors" => [
          [
            "id" => 17,
            "character" => "Nick Fury"
          ]
        ]
      ];

      $this->json('POST', route('api.movies.store'), $data)
           ->assertStatus(201)
           ->assertJsonStructure([
             "*" => [
               "id",
               "name",
               "year",
               "rating",
               "description",
               "images" => [
                 "*" => [
                   "*" => [
                     "id",
                     "url",
                     "mime",
                     "size"
                   ]
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

    public function test_it_rejects_malformed_create_requests() {
      $data = ["invalid_key" => "name"];
      $this->json('POST', route('api.movies.store'), $data)
           ->assertStatus(400);
    }

    public function test_it_can_show_a_movie() {
      $this->json("GET", route('api.movies.show', 1))
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

    public function test_it_can_delete_movies() {
      $id = 5;
      $this->json('DELETE', route('api.movies.delete', $id))
           ->assertStatus(204);

      $this->json('GET', route('api.movies.show', $id))
          ->assertStatus(404);
    }

    public function test_it_can_update_movies() {
      $data = [
        "name" => "Updated Title",
        "year" => "2019",
        "rating" => "10.0",
        "description" => "Updated Description",
        "actorsSync" => [
          ["id" => 1, "character" => "Tony"]
        ],
        "actorsAttach" => [
          ["id" => 2, "character" => "Tony"]
        ],
        "actorsDetach" => [1],
        "genresSync" => [1,2,3,4],
        "genresAttach" => [5,6],
        "genresDetach" => [2,3,6]
      ];
      $this->json('PUT', route('api.movies.update', 1), $data)
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
