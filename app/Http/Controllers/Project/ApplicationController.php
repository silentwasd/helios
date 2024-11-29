<?php

namespace App\Http\Controllers\Project;

use App\Enums\ApplicationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index(Project $project)
    {
        return ApplicationResource::collection($project->applications);
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'application_type' => ['required', 'string', 'max:255', Rule::enum(ApplicationType::class)]
        ]);

        $application = $project->applications()->create($data);

        return new ApplicationResource($application);
    }

    public function show(Project $project, Application $application)
    {
        return new ApplicationResource($application);
    }

    public function update(Request $request, Project $project, Application $application)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $application->update($data);
    }

    public function destroy(Project $project, Application $application)
    {
        $application->delete();
    }
}
