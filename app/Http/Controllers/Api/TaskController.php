<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;


class TaskController extends Controller
{
    use ApiResponse;

    public function my_tasks()
    {
        $tasks = Task::where('Assignee', auth()->user()->id)->get();

        return $this->apiResponse(TaskResource::collection($tasks), 'Ok', JsonResponse::HTTP_OK);
    }
    public function other_tasks()
    {
        $tasks = Task::where('Assignee', '!=', auth()->user()->id)->get();

        return $this->apiResponse(TaskResource::collection($tasks), 'Ok', JsonResponse::HTTP_OK);
    }
    public function store(TaskCreateRequest $request)
    {
        $Task = Task::create($request->validated());
        if ($Task) {
            return $this->apiResponse($Task, 'The Task Save', JsonResponse::HTTP_CREATED);
        }
        return $this->apiResponse(null, 'The Task Not Save', JsonResponse::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {
        $task = Task::where('Assigner', auth()->user()->id)->find($id)->delete();
        if (!$task) {
            return $this->apiResponse(null, 'The task Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        if ($task) {
            return $this->apiResponse(null, 'The task deleted', JsonResponse::HTTP_OK);
        }
    }
    public function update(TaskUpdateRequest $request, $id)
    {
        $task = Task::where('Assigner', auth()->user()->id)->find($id);

        if (!$task) {
            return $this->apiResponse(null, 'The task Not Found', JsonResponse::HTTP_NOT_FOUND);
        }

        $task->update($request->validated());

        if ($task) {
            return $this->apiResponse(new TaskResource($task), 'The task update', JsonResponse::HTTP_CREATED);
        }
    }
}
