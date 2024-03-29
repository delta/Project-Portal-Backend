<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Stack;

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
        $stacks = Stack::all();
        Project::all()->each(function ($project) use ($stacks) {
            $project->stacks()->attach(
                $stacks->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
