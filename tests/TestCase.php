<?php

namespace Tests;

use Faker\Factory;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $faker;

    public function setUp() {
        parent::setUp();
        $this->faker = Factory::create();
        // $this->passport = new Passport;
        Passport::actingAs(
            factory(User::class)->create()
        );
    }
}
