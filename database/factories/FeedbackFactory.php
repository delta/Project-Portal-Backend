<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'project_id' => function() {
            return factory(App\Models\User::class)->create()->id;
        },
        'sender_id' => App\Models\User::inRandomOrder()->value('id'),
        'receiver_id' => App\Models\User::inRandomOrder()->value('id'),
        'content' => $faker->text
    ];
});
