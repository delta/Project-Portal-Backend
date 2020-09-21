<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Project;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition()
    {
        return [
            'project_id' => function () {
                return Project::factory()->make()->id;
            },
            'sender_id' => User::inRandomOrder()->value('id'),
            'receiver_id' => User::inRandomOrder()->value('id'),
            'content' => $this->faker->text
        ];
    }
}
