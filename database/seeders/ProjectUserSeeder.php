<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;

use Illuminate\Database\Seeder;

class ProjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();
        User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 2))->pluck('id')->toArray(),
                ['role' => 'DEVELOPER']
            );
        });
        User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'MAINTAINER']
            );
        });
        User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'AUTHOR']
            );
        });
    }
}
