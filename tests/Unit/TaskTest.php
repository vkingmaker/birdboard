<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class TaskTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_task_belongs_to_a_project()
    {
        $this->withoutExceptionHandling();

        $task = factory('App\Task')->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function task_has_a_path()
    {
        $this->withoutExceptionHandling();

        $task = factory('App\Task')->create();

        $this->assertEquals($task->path(), $task->path());
    }
}
