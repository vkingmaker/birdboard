<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class UserTest extends TestCase
{

  use RefreshDatabase;

  /** @test */
  public function a_user_has_project()
  {
    $user = factory('App\User')->create();

    $this->assertInstanceOf(Collection::class,$user->projects);
  }

  /** @test */
  public function it_belongs_to_an_owner()
  {

    $project = factory('App\Project')->create();

    $this->assertInstanceOf('App\User', $project->owner);
  }

  /** @test */
  public function a_user_has_accessible_projects()
  {
      $john = $this->signIn();

      ProjectFactory::ownedBy($john)->create();

      $this->assertCount(1, $john->accessibleProjects());

      $sally = factory(User::class)->create();

      $nick = factory(User::class)->create();

      $project = tap(ProjectFactory::ownedBy($sally)->create())->invite($nick);


      $this->assertCount(1, $john->accessibleProjects());

      $project->invite($john);

      $this->assertCount(2, $john->accessibleProjects());

  }
}
