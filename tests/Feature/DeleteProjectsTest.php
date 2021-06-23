<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Models\Stack;
use App\Models\Status;
use App\Models\Type;
use Laravel\Passport\Passport;

class DeleteProjectsTest extends TestCase
{
    /** @test */
    public function delete_project_route_is_guarded()
    {
        $this->post('api/projects/1/delete')
            ->assertUnauthorized();
    }

    /** @test */
    public function only_authors_can_delete_the_project()
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

        Passport::actingAs($author);
        $this->post('api/projects/' . $project->id . '/delete')
            ->assertOk()
            ->assertJson([
                'message' => 'Project deleted successfully!'
            ]);
    }

    /** @test */
    public function maintainers_and_developers_cannot_delete_project()
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

        Passport::actingAs($maintainer);
        $this->post('api/projects/' . $project->id . '/delete')
            ->assertForbidden();

        Passport::actingAs($developer);
        $this->post('api/projects/' . $project->id . '/delete')
            ->assertForbidden();
    }

    /** @test */
    public function project_can_be_deleted()
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

        $this->assertContains(
            $project->id,
            $author->projects()->get()->pluck('id')
        );
        $this->assertContains(
            $project->id,
            $maintainer->projects()->get()->pluck('id')
        );

        Passport::actingAs($author);
        $this->post('api/projects/' . $project->id . '/delete')
            ->assertOk()
            ->assertJson([
                'message' => 'Project deleted successfully!'
            ]);

        $this->assertNotContains(
            $project->id,
            $author->projects()->get()->pluck('id')
        );
        $this->assertNotContains(
            $project->id,
            $maintainer->projects()->get()->pluck('id')
        );
    }

    /** @test */
    public function project_is_only_soft_deleted()
    {
        $author = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->syncWithoutDetaching([
            $author->id =>
                ['role' => 'AUTHOR']
        ]);

        Passport::actingAs($author);
        $this->post('api/projects/' . $project->id . '/delete')
            ->assertOk()
            ->assertJson([
                'message' => 'Project deleted successfully!'
            ]);

        $this->assertSoftDeleted('projects', [
            'id' => $project->id
        ]);
    }
}
