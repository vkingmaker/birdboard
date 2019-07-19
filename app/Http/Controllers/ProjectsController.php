<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    /**
     *  Retrieve all the projects from the database.
     *
     * @return  App\Project  $projects
     */

    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    /**
     *  Validates and saves projects to the database.
     *
     */

    public function store()
    {

        $attributes = request()->validate([

                'title' => 'required',

                'description' => 'required',

            ]);

        auth()->user()->projects()->create($attributes);

        // Project::create($attributes);

        return redirect('/projects');
    }

    /**
     * @param  App\Project  $project
     * @return
     */

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }
}
