<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

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
    $this->withoutExceptionHandling();

    $project = factory('App\Project')->create();

    $this->assertInstanceOf('App\User', $project->owner);
  }
}
