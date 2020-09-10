<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Feedback;

use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::All()->each(function ($project) {
            $project->feedbacks()->saveMany(
                Feedback::factory()
                    ->count(rand(3, 10))
                    ->create([
                        'project_id' => $project->id
                    ])
            );
        });
    }
}
