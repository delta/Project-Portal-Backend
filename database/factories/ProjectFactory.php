<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'description' => $faker->text,
        'type_id' => App\Type::inRandomOrder()->value('id'),
        'status_id' => App\Status::inRandomOrder()->value('id'),
        'repo_link' => 'github.com/'.$faker->firstName,
        'max_member_count' => $faker->numberBetween(1, 10),
        'deadline' => $faker->dateTimeThisYear,
        'review' => $faker->text
    ];
});
