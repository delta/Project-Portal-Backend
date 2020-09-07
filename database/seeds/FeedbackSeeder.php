<?php

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
        App\Models\Project::All()->each(function ($project) {
            $project->feedbacks()->saveMany(
                factory(App\Models\Feedback::class, rand(3, 10))->create(['project_id' => $project->id])
            );
        });
    }
}
