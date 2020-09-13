<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use Laravel\Passport\Passport;

class EditProjectsTest extends TestCase
{
    private $project;
    private $users, $developer, $maintainer, $author;


    public function setUp(): void
    {
        parent::setUp();

        $this->project = Project::factory()->create();
        $this->users = User::factory()->count(3)->create();

        $this->developer = $this->users[0];
        $this->maintainer = $this->users[1];
        $this->author = $this->users[2];

        $this->project->users()->syncWithoutDetaching([
            $this->developer->id =>
            ['role' => 'DEVELOPER'],
            $this->maintainer->id =>
            ['role' => 'MAINTAINER'],
            $this->author->id =>
            ['role' => 'AUTHOR']
        ]);
    }

    /** @test */
    public function edit_project_route_is_guarded()
    {
        $this->post('api/projects/1/edit')
            ->assertStatus(401);
    }

    /** @test */
    public function authors_and_maintainers_can_edit_their_project()
    {
        $data = $this->project->toArray();
        $data['stacks'] = [1, 2];
        $data['status'] = 1;
        $data['type'] = 1;
        $data['deadline'] = null;

        Passport::actingAs($this->maintainer);
        $this->post(
            'api/projects/1/edit',
            $data
        )->assertStatus(200)
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        Passport::actingAs($this->author);
        $this->post(
            'api/projects/1/edit',
            $data
        )->assertStatus(200)
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);
    }

    /** @test */
    public function developers_cannot_edit_their_project()
    {
        $data = $this->project->toArray();
        $data['stacks'] = [1, 2];
        $data['status'] = 1;
        $data['type'] = 1;
        $data['deadline'] = null;

        Passport::actingAs($this->developer);
        $this->post(
            'api/projects/1/edit',
            $data
        )->assertStatus(403)
            ->assertJson([
                'message' => 'You are not allowed to edit this project!'
            ]);
    }
}
