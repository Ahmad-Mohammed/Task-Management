<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware(['jwt.verify'])->group(function () {
    
    Route::get('/my_tasks', [TaskController::class, 'my_tasks']);
    Route::get('/other_tasks', [TaskController::class, 'other_tasks']);
    Route::post('/task/create', [TaskController::class, 'store']);
    Route::get('/task/delete/{id}', [TaskController::class, 'destroy']);
    Route::post('/task/update/{id}', [TaskController::class, 'update']);

    Route::get('/my_projects', [ProjectController::class, 'my_projects']);
    Route::get('/my_project/task/{id}', [ProjectController::class, 'my_TaskProjects']);
    Route::get('/project/delete/{id}', [ProjectController::class, 'destroy']);
    Route::post('/project/create', [ProjectController::class, 'store']);
    Route::post('/project/update/{id}', [ProjectController::class, 'update']);

    Route::get('/my_profile', [UserController::class, 'my_profile']);
    Route::post('/update/profile', [UserController::class, 'update_profile']);

    Route::middleware('admin')->group(function () {

        Route::get('/tasks', [AdminController::class, 'tasks']);
        Route::get('/any_task/delete/{id}', [AdminController::class, 'destroy_task']);
        Route::post('/any_task/update/{id}', [AdminController::class, 'update_task']);
        Route::get('/projects', [AdminController::class, 'projects']);
        Route::get('/project/tasks/{id}', [AdminController::class, 'TaskProjects']);
        Route::get('/any_project/delete/{id}', [AdminController::class, 'destroy_project']);
        Route::post('/any_project/update/{id}', [AdminController::class, 'update_project']);
        Route::post('/new_user', [AdminController::class, 'new_user']);
        Route::get('/users', [AdminController::class, 'view_users']);
        Route::get('/user/delete/{id}', [AdminController::class, 'delete_user']);
        Route::post('/user/update/{id}', [AdminController::class, 'update_user']);
    });
});
