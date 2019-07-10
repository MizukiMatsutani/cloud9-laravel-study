<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Eloquents\Pin;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Pin::class, function (Faker $faker) {
    return [
        'friends_id' => factory(\App\Eloquents\Friend::class)->create()->id,
        'latitude' => $faker->latitude($min = 20, $max = 45),
        'longitude' => $faker->longitude($min = 122, $max = 153),
    ];
});
