<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Models\Feedback;
use Laravel\Passport\Passport;

class FetchProjectTest extends TestCase
{
    /** @test */
    public function fetch_project_routes_are_guarded()
    {
        $project = Project::factory()->create();

        $this->get('api/projects/all')
            ->assertUnauthorized();
        $this->get('api/projects/' . $project->id)
            ->assertUnauthorized();
    }

    /** @test */
    public function all_projects_can_be_fetched()
    {
        $registeredUser = User::factory()->create();

        Passport::actingAs($registeredUser);
        $this->get('api/projects/all')
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'projects' => [
                        '*' =>  [
                            'stacks',
                            'status',
                            'type'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function select_project_can_be_fetched()
    {
        $project = Project::factory()->create();
        $developer = User::factory()->create();
        $project->users()->syncWithoutDetaching([
            $developer->id => ['role' => 'DEVELOPER']
        ]);

        Passport::actingAs($developer);
        $this->get('api/projects/' . $project->id)
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'project' => [
                        '*' =>  [
                            'feedbacks',
                            'stacks',
                            'status',
                            'users',
                            'type'
                        ]
                    ]
                ]
            ]);
    }
}
