<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    $rollNumber = $faker->unique()->numberBetween(100000000, 109999999);
    return [
        'roll_number' => $rollNumber,
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt($rollNumber),
        'github_handle' => $faker->firstName
    ];
});
