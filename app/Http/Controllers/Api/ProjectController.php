<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TasksProjectResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    use ApiResponse;



    public function my_projects()
    {
        $projects = Project::where('Assigner',auth()->user()->id)->get();

        return $this->apiResponse(ProjectResource::collection($projects), 'Ok', JsonResponse::HTTP_OK);

    }
    public function my_TaskProjects($id)
    {
        $tasks = Task::where('Assigner', auth()->user()->id)->where('project_id', $id)->get();

        return $this->apiResponse(TasksProjectResource::collection($tasks), 'Ok', JsonResponse::HTTP_OK);

    }

    public function store(ProjectCreateRequest $request)
    {
        $project = Project::create($request->validated());
        if ($project) {
            return $this->apiResponse($project, 'The project Save', JsonResponse::HTTP_CREATED);
        }
        return $this->apiResponse(null, 'The project Not Save', JsonResponse::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {

        $project = Project::where('Assigner', auth()->user()->id)->find($id)->delete();
        if (!$project) {
            return $this->apiResponse(null, 'The project Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        if ($project) {
            return $this->apiResponse(null, 'The project deleted', JsonResponse::HTTP_OK);
        }
    }
    public function update(ProjectUpdateRequest $request, $id)
    {

        $project = Project::where('Assigner', auth()->user()->id)->find($id);
        if (!$project) {
            return $this->apiResponse(null, 'The project Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        $project->update($request->validated());
        if ($project) {
            return $this->apiResponse(new ProjectResource($project), 'The project update', JsonResponse::HTTP_CREATED);
        }
    }
}
