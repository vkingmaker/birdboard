<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path().'/tasks', ['body' => 'text task']);

        $this->get($project->path())->assertSee('text task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)->create();


        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[

            'body' => 'changed',
        ]);

        $this->assertDatabaseHas('tasks', [

            'body' => 'changed',
        ]);

    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();


        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[

            'body' => 'changed',

            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [

            'body' => 'changed',

            'completed' => true,
        ]);

    }

        /** @test */
        public function a_task_can_be_marked_as_incomplete()
        {
            $this->withoutExceptionHandling();

            $project = ProjectFactory::withTasks(1)->create();


            $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[

                'body' => 'changed',

                'completed' => true
            ]);

            $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[

                'body' => 'changed',

                'completed' => false
            ]);

            $this->assertDatabaseHas('tasks', [

                'body' => 'changed',

                'completed' => false,
            ]);

        }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path().'/tasks', ['body' => 'test task'])

            ->assertStatus(403);

    }

    /** @test */
    public function only_the_owner_of_a_project_can_update_a_tasks()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])

            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);

    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $attributes = factory('App\Project')->raw(['body' => '']);

        $this->actingAs($project->owner)

                ->post($project->path().'/tasks', $attributes)

                ->assertSessionHasErrors('body');
    }
}
