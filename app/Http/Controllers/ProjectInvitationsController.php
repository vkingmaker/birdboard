<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\Http\Requests\ProjectInvitationRequest;

class ProjectInvitationsController extends Controller
{

    /**
     * Invite a new user to the project
     *
     * @param  Project  $project
     * @param  ProjectInvitationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $user = User::whereEmail(request('email'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
