<?php

use Illuminate\Database\Seeder;

class ProjectStackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stacks = App\Models\Stack::all();
        App\Models\Project::all()->each(function ($project) use ($stacks) {
            $project->stacks()->attach(
                $stacks->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
