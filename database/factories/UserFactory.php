<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'roll_number' => $faker->unique()->numberBetween(100000000, 109999999),
        'name' => $faker->name,
        'github_link' => 'github.com/'.$faker->firstName
    ];
});
