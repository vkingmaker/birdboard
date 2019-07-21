<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Task;

$factory->define(Task::class, function (Faker $faker) {
    return [

        'body' => $faker->paragraph,

        'completed' => false,

        'project_id' => factory('App\Project')->create()->id,
    ];
});

