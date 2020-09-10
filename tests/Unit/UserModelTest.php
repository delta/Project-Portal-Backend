<?php

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;

class UserModelTest extends TestCase
{
    /**
     * Tests all the relations of a User
     *
     */

    private $project;
    private $users;

    public function setUp(): void
    {

        parent::setUp();
        Schema::disableForeignKeyConstraints();

        $this->project = Project::factory()->create();
        $this->users = User::factory()->count(4)->create();
    }

    /** @test */
    public function user_can_belong_to_a_project()
    {

        $this->users[0]->projects()->syncWithoutDetaching([
            $this->project->id =>
            ['role' => 'DEVELOPER']
        ]);
        $firstUserRole = $this->project->users()->first()->pivot->role;
        $this->assertEquals($firstUserRole, 'DEVELOPER');
    }

    public function tearDown(): void
    {

        $this->users->each(function ($user) {
            $user->delete();
        });
        $this->project->delete();

        Schema::enableForeignKeyConstraints();
        parent::tearDown();
    }
}
