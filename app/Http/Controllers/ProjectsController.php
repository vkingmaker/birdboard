<?php

namespace App\Http\Controllers;

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
     *  Persist a new project
     *
     *  @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {

        $attributes = request()->validate([

                'title' => 'required',

                'description' => 'required',

                'notes' => 'min:3'

            ]);

            // dd($attributes);

       $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    /**
     * @param  App\Project  $project
     * @return
     */

    public function show(Project $project)
    {
        $this->authorize('update',$project);

        return view('projects.show', compact('project'));
    }


    public function update(Project $project)
    {
        $this->authorize('update',$project);

        $project->update(request(['notes']));

        return redirect($project->path());

    }
}
