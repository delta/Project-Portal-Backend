<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use Laravel\Passport\Passport;

class AddProjectsTest extends TestCase
{
    private array $projectData;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectData = Project::factory()->make()->toArray();
        $this->projectData['stacks'] = [1, 2];
        $this->projectData['status'] = 1;
        $this->projectData['type'] = 1;
        $this->projectData['enddate'] = '2021-06-24 00:00:00';
        $this->projectData['startdate'] = '2021-06-21 00:00:00';
    }

    /** @test */
    public function add_project_route_is_guarded()
    {
        $this->post('api/projects/add')
            ->assertUnauthorized();
    }

    /** @test */
    public function projects_can_be_added()
    {
        $newProjectPostData = $this->projectData;
        $author = User::factory()->create();

        Passport::actingAs($author);
        $this->post(
            'api/projects/add',
            $newProjectPostData
        )->assertOk()
            ->assertJson([
                'message' => 'Project created successfully!',
            ]);
    }

    /** @test */
    public function user_who_added_the_project_becomes_author()
    {
        $newProjectPostData = $this->projectData;
        $author = User::factory()->create();

        Passport::actingAs($author);
        $this->post(
            'api/projects/add',
            $newProjectPostData
        )->assertOk()
            ->assertJson([
                'message' => 'Project created successfully!',
            ]);
        $this->assertEquals(
            $author->projects()->first()->pivot->role,
            'AUTHOR'
        );
    }

    /** @test */
    public function users_can_be_added_with_projects()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $newProjectPostData = $this->projectData;
        $newProjectPostData['users'] = [
            [
                "id" => $maintainer->id,
                "role" => "MAINTAINER"
            ],
            [
                "id" => $developer->id,
                "role" => "DEVELOPER"
            ]
        ];

        Passport::actingAs($author);
        $this->post(
            'api/projects/add',
            $newProjectPostData
        )->assertOk()
            ->assertJson([
                'message' => 'Project created successfully!',
            ]);

        $addedProject = Project::all()->last();
        $this->assertContains(
            $addedProject->id,
            $author->projects()->get()->pluck('id')
        );
        $this->assertContains(
            $addedProject->id,
            $maintainer->projects()->get()->pluck('id')
        );
        $this->assertContains(
            $addedProject->id,
            $developer->projects()->get()->pluck('id')
        );

    }

    /** @test */
    public function new_project_cannot_have_existing_repo_link()
    {
        $existingProject = Project::factory()->create();
        $author = User::factory()->create();

        $newProjectPostData = $this->projectData;
        $newProjectPostData['repo_link'] =
            $existingProject->repo_link;

        Passport::actingAs($author);
        $this->post(
            'api/projects/add',
            $newProjectPostData
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'repo_link' => [
                        "The repo link has already been taken."
                    ]
                ]
            ]);
    }
}
