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
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    /**
     * Create a Project
     *
     */


     public function create()
     {
         return view('projects.create');
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

        return redirect('/projects');
    }

    /**
     * @param  App\Project  $project
     * @return
     */

    public function show(Project $project)
    {
        if(auth()->user()->isNot($project->owner)) {

            abort(403);

        }

        return view('projects.show', compact('project'));
    }
}
