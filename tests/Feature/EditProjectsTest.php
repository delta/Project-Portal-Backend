<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use Laravel\Passport\Passport;

class EditProjectsTest extends TestCase
{
    private function get_post_data_from($project)
    {
        $data = $project->toArray();
        $data['stacks'] = [1, 2];
        $data['status'] = 1;
        $data['type'] = 1;
        $data['enddate'] = '2021-06-24 00:00:00';
        $data['startdate'] = '2021-06-21 00:00:00';
        return $data;
    }

    /** @test */
    public function edit_project_route_is_guarded()
    {
        $this->post('api/projects/1/edit')
            ->assertStatus(401);
    }

    /** @test */
    public function projects_can_be_edited()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $NAME_BEFORE_EDIT = 'Test Project 1';
        $NAME_AFTER_EDIT = 'Test Project 2';

        $project = Project::factory()->create([
            'name' => $NAME_BEFORE_EDIT
        ]);
        $project->users()->syncWithoutDetaching([
            $developer->id => ['role' => 'DEVELOPER'],
            $maintainer->id => ['role' => 'MAINTAINER'],
            $author->id => ['role' => 'AUTHOR']
        ]);

        $this->assertEquals(
            $NAME_BEFORE_EDIT,
            $project->name
        );

        $data = $this->get_post_data_from($project);
        $data['name'] = $NAME_AFTER_EDIT;

        Passport::actingAs($maintainer);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertOk()
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        $project->refresh();
        $this->assertEquals(
            $NAME_AFTER_EDIT,
            $project->name
        );
    }

    /** @test */
    public function authors_and_maintainers_can_edit_their_project()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();

        $NAME_BEFORE_EDIT = 'Test Project 1';
        $NAME_AFTER_EDIT_1 = 'Test Project 2';
        $NAME_AFTER_EDIT_2 = 'Test Project 3';

        $project = Project::factory()->create([
            'name' => $NAME_BEFORE_EDIT
        ]);
        $project->users()->syncWithoutDetaching([
            $maintainer->id => ['role' => 'MAINTAINER'],
            $author->id => ['role' => 'AUTHOR']
        ]);

        $this->assertEquals(
            $NAME_BEFORE_EDIT,
            $project->name
        );

        $data = $this->get_post_data_from($project);
        $data['name'] = $NAME_AFTER_EDIT_1;

        Passport::actingAs($maintainer);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertOk()
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        $project->refresh();
        $this->assertEquals(
            $NAME_AFTER_EDIT_1,
            $project->name
        );

        $data['name'] = $NAME_AFTER_EDIT_2;

        Passport::actingAs($author);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertOk()
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        $project->refresh();
        $this->assertEquals(
            $NAME_AFTER_EDIT_2,
            $project->name
        );
    }

    /** @test */
    public function developers_cannot_edit_their_project()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $NAME_BEFORE_EDIT = 'Test Project 1';
        $NAME_AFTER_EDIT = 'Test Project 2';

        $project = Project::factory()->create([
            'name' => $NAME_BEFORE_EDIT
        ]);
        $project->users()->syncWithoutDetaching([
            $developer->id => ['role' => 'DEVELOPER'],
            $maintainer->id => ['role' => 'MAINTAINER'],
            $author->id => ['role' => 'AUTHOR']
        ]);

        $this->assertEquals(
            $NAME_BEFORE_EDIT,
            $project->name
        );

        $data = $this->get_post_data_from($project);
        $data['name'] = $NAME_AFTER_EDIT;

        Passport::actingAs($developer);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertForbidden()
            ->assertJson([
                'message' => 'You are not allowed to edit this project!'
            ]);

        $this->assertEquals(
            $NAME_BEFORE_EDIT, // Nothing has changed
            $project->name
        );
    }

    /** @test */
    public function users_can_be_added_to_project()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->syncWithoutDetaching([
            $author->id => ['role' => 'AUTHOR']
        ]);

        $this->assertCount(
            1,
            $project->users()->get()
        );

        $data = $this->get_post_data_from($project);
        $data['users'] = [
            ['id' => $developer->id, 'role' => 'DEVELOPER'],
            ['id' => $maintainer->id, 'role' => 'MAINTAINER']
        ];

        Passport::actingAs($author);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertOk()
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        $this->assertCount(
            3,
            $project->users()->get()
        );
    }

    /** @test */
    public function users_roles_in_project_can_be_modified()
    {
        $author = User::factory()->create();
        $maintainer = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->syncWithoutDetaching([
            $developer->id => ['role' => 'DEVELOPER'],
            $maintainer->id => ['role' => 'MAINTAINER'],
            $author->id => ['role' => 'AUTHOR']
        ]);

        $oldDeveloperNewMaintainer = $developer;
        $data = $this->get_post_data_from($project);
        $data['users'] = [
            ['id' => $maintainer->id, 'role' => 'MAINTAINER'],
            ['id' => $oldDeveloperNewMaintainer->id, 'role' => 'MAINTAINER'],
        ];

        Passport::actingAs($maintainer);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertOk()
            ->assertJson([
                'message' => 'Project edited successfully!'
            ]);

        $this->assertEquals(
            $oldDeveloperNewMaintainer->projects()->first()->pivot->role,
            'MAINTAINER'
        );
    }

    /** @test */
    public function author_cannot_have_any_other_role()
    {
        $author = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->syncWithoutDetaching([
            $author->id => ['role' => 'AUTHOR']
        ]);

        $data = $this->get_post_data_from($project);
        $data['users'] = [
            ['id' => $author->id, 'role' => 'MAINTAINER'],
        ];

        Passport::actingAs($author);
        $this->post(
            'api/projects/' . $project->id . '/edit',
            $data
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'users' => 'Author cannot take any other role'
                ]
            ]);
    }
}
