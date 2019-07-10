<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Eloquents\FriendsRelationship;
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

$factory->define(FriendsRelationship::class, function (Faker $faker) use ($factory) {
    return [
        'own_friends_id' => factory(\App\Eloquents\Friend::class)->create()->id,
        'other_friends_id' => factory(\App\Eloquents\Friend::class)->create()->id,
    ];
});
