<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        abort_unless($project->published, 404);

        $project->load([
            'technologies:id,name,slug',
            'images:id,project_id,path,alt,sort_order',
        ]);

        return view('pages.projects.show', compact('project'));
    }
}
