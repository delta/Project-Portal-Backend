<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'project_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'sender_id' => App\User::inRandomOrder()->value('id'),
        'receiver_id' => App\User::inRandomOrder()->value('id'),
        'content' => $faker->text
    ];
});
