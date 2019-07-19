<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\FactoryBuilder;

class ProjectsTest extends TestCase
{

    use RefreshDatabase, WithFaker;

      /** @test */
      public function only_authenticated_users_can_create_projects()
      {
          $attribute = factory('App\Project')->raw();

          $this->post('/projects', $attribute)->assertRedirect('/login');
      }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());

        $attributes = [

            'title' => $this->faker->sentence,

            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_a_project()
    {

        $project = factory('App\Project')->create();

        $this->get($project->path())

             ->assertSee($project->title)

             ->assertSee($project->description);
    }

    /** @test */
    public function a_project_should_have_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attribute = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_project_should_have_a_descripton()
    {
        $this->actingAs(factory('App\User')->create());

        $attribute = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('description');

    }


}
