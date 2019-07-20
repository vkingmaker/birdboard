<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

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

            'description' => $this->faker->sentence,

            'notes' => 'General notes here',
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())

            ->assertSee($attributes['title'])

            ->assertSee($attributes['description'])

            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->patch($project->path(), $attribute = [

            'notes' => 'Changed'

        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attribute);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->get($project->path())

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
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), [])->assertStatus(403);

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
