<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

    use RefreshDatabase, WithFaker;

      /** @test */
      public function guests_cannot_manage_projects()
      {
        $project = factory('App\Project')->create();

        $this->post('/projects', $project->toArray())->assertRedirect('/login');

        $this->get('/projects/create')->assertRedirect('/login');

        $this->get('/projects')->assertRedirect('/login');

        $this->get($project->path())->assertRedirect('/login');
      }

    /** @test */
    public function a_user_can_create_a_project()
    {

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [

            'title' => $this->faker->sentence,

            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {

        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())

             ->assertSee($project->title)

             ->assertSee($project->description);
    }


    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    /** @test */
    public function a_project_should_have_a_title()
    {
        $this->signIn();

        $attribute = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_project_should_have_a_descripton()
    {
        $this->signIn();

        $attribute = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('description');

    }


}
