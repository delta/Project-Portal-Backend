<?php

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
        $projects = App\Models\Project::all();
        App\Models\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 2))->pluck('id')->toArray(),
                ['role' => 'DEVELOPER']
            );
        });
        App\Models\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'MAINTAINER']
            );
        });
        App\Models\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'AUTHOR']
            );
        });
    }
}
