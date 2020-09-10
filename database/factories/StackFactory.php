<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Stack;
use Faker\Generator as Faker;

$factory->define(Stack::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName
    ];
});