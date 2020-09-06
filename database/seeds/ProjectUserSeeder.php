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
        $projects = App\Project::all();
        App\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 2))->pluck('id')->toArray(),
                ['role' => 'DEVELOPER']
            );
        });
        App\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'MAINTAINER']
            );
        });
        App\User::all()->each(function ($user) use ($projects) {
            $user->projects()->attach(
                $projects->random(rand(0, 1))->pluck('id')->toArray(),
                ['role' => 'AUTHOR']
            );
        });
    }
}
