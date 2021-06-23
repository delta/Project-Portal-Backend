<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Models\Feedback;

class ProjectModelTest extends TestCase
{
    /**
     * Test all relations of a Project
     *
     */

    private $project;
    private $users;

    public function setUp(): void
    {

        parent::setUp();

        $this->project = Project::factory()->create();
        $this->users = User::factory()->count(4)->create();
    }

    /** @test */
    public function project_can_have_users()
    {
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::factory()->create();

        $project->users()->syncWithoutDetaching([
            $maintainer->id => ['role' => 'MAINTAINER'],
            $developer->id => ['role' => 'DEVELOPER']
        ]);
        $firstUserRole = $project->users()->first()->pivot->role;
        $this->assertEquals('MAINTAINER', $firstUserRole);
    }

    /** @test */
    public function roles_can_be_updated_for_project_members()
    {
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::factory()->create();

        $project->users()->syncWithoutDetaching([
            $maintainer->id => ['role' => 'MAINTAINER']
        ]);

        $convertedToDeveloper = $maintainer;
        $response = $project->users()->syncWithoutDetaching([
            $convertedToDeveloper->id => ['role' => 'DEVELOPER']
        ]);
        $this->assertCount(1, $response['updated']);
        $this->assertEquals($project->users()->first()->pivot->role, 'DEVELOPER');
    }


    /** @test */
    public function no_duplicate_user_role_pair_per_project()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::factory()->create();

        $project->users()->syncWithoutDetaching([
            $author->id => ['role' => 'AUTHOR'],
            $maintainer->id => ['role' => 'MAINTAINER'],
            $maintainer->id => ['role' => 'MAINTAINER'],
            $developer->id => ['role' => 'DEVELOPER']
        ]);
        $this->assertCount(3, $project->users()->get());
    }
}
