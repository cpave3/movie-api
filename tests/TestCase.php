<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, WithoutMiddleware;

    protected $faker;

    // Set up the test
    public function setUp() {
      parent::setUp();
      $this->fasker = Faker::create();
      $this->seed();
    }

    public function tearDown() {
      $this->artisan('migrate:reset');
      parent::tearDown();
    }
}
