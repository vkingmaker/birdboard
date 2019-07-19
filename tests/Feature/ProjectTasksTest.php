<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(

            factory(Project::class)->raw()
        );

        $this->post($project->path().'/tasks', ['body' => 'text task']);

        $this->get($project->path())->assertSee('text task');
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
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(

            factory(Project::class)->raw()
        );

        $attributes = factory('App\Project')->raw(['body' => '']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
