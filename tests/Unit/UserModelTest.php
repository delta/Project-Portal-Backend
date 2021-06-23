<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\Project;
use App\Models\User;

class UserModelTest extends TestCase
{
    /**
     * Tests all the relations of a User
     *
     */

    /** @test */
    public function user_can_belong_to_a_project()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $user->projects()->syncWithoutDetaching([
            $project->id =>
            ['role' => 'DEVELOPER']
        ]);
        $firstUserRole = $project->users()->first()->pivot->role;
        $this->assertEquals($firstUserRole, 'DEVELOPER');
    }
}
