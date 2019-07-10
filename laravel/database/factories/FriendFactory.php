<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Eloquents\Friend;
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

$factory->define(Friend::class, function (Faker $faker) {
    return [
        'nickname' => $faker->name($gender = null),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'image_path' => null,
        'remember_token' => Str::random(10),
    ];
});
