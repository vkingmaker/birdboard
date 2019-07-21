<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    /**
     * The relationships that should be touched on save
     *
     * @var  array
     */

    protected $touches = ['project'];

    protected $casts = [

        'completed' => 'boolean'
    ];


    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }


    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->project->recordActivity('incompleted_task');
    }


    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * The activity feed for the project
     *
     * @return \Illuminate\Database\Eloquent\Relationships\HasMany
     */

    public function recordActivity($description)
    {
        $this->activity()->create([

            'project_id' => $this->project_id,

            'description' => $description,
        ]);
    }
}
