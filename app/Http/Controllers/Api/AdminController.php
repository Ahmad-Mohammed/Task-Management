<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\AdminResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksProjectResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    use ApiResponse;

    public function tasks()
    {
        return $this->apiResponse(TaskResource::collection(Task::all()), 'Ok', JsonResponse::HTTP_OK);
    }

    public function destroy_task($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->apiResponse(null, 'The task Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        $task->delete($id);
        if ($task) {
            return $this->apiResponse(null, 'The task deleted', JsonResponse::HTTP_OK);
        }
    }
    public function update_task(TaskUpdateRequest $request, $id)
    {

        $task = Task::find($id);
        if (!$task) {
            return $this->apiResponse(null, 'The task Not Found', 404);
        }
        $task->update($request->validated());
        if ($task) {
            return $this->apiResponse(new TaskResource($task), 'The task update', 201);
        }
    }






    public function projects()
    {
        return $this->apiResponse(ProjectResource::collection(Project::all()), 'Ok', JsonResponse::HTTP_OK);
    }

    public function TaskProjects($id)
    {
        $tasks = Task::where('project_id', $id)->get();

        return $this->apiResponse(TasksProjectResource::collection($tasks), 'Ok', JsonResponse::HTTP_OK);
    }
    public function destroy_project($id)
    {

        $project = Project::find($id)->delete();
        if (!$project) {
            return $this->apiResponse(null, 'The project Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        if ($project) {
            return $this->apiResponse(null, 'The project deleted', JsonResponse::HTTP_OK);
        }
    }
    public function update_project(ProjectUpdateRequest $request, $id)
    {

        $project = Project::find($id);
        if (!$project) {
            return $this->apiResponse(null, 'The project Not Found', JsonResponse::HTTP_NOT_FOUND);
        }

        $project->update($request->validated());
        if ($project) {
            return $this->apiResponse(new ProjectResource($project), 'The project update', JsonResponse::HTTP_CREATED);
        }
    }

    public function new_user(AdminRequest $request)
    {

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('user/image', $request->image, $imageName);

        $user = User::create($request->post() + ['image' => $imageName]);
        if ($user) {
            return $this->apiResponse($user, 'The User Save', JsonResponse::HTTP_CREATED);
        }
        return $this->apiResponse(null, 'User not save ', JsonResponse::HTTP_CREATED);
        
    }
    public function view_users()
    {
        return $this->apiResponse(AdminResource::collection(User::all()), 'Ok', JsonResponse::HTTP_OK);
    }
    public function delete_user($id)
    {

        $user = User::find($id);
        if (!$user) {
            return $this->apiResponse(null, 'The user Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        if ($user->image) {
            $exist = Storage::disk('public')->exists("user/image/{$user->image}");
            if ($exist) {
                Storage::disk('public')->delete("user/image/{$user->image}");
            }
        }
        $user->delete();
        if ($user) {
            return $this->apiResponse($user, 'The user deleted', JsonResponse::HTTP_OK);
        }
    }
    public function update_user(AdminRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->apiResponse(null, 'The user Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        $user->fill($request->validated())->update();
        if ($request->hasFile('image')) {
            if ($user->image) {
                $exist = Storage::disk('public')->exists("user/image/{$user->image}");
                if ($exist) {
                    Storage::disk('public')->delete("user/image/{$user->image}");
                }
            }
            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('user/image', $request->image, $imageName);
            $user->image = $imageName;
            $user->save();
        }

        if ($user) {
            return $this->apiResponse(new AdminResource($user), 'The user update', JsonResponse::HTTP_CREATED);
        }
    }
}
