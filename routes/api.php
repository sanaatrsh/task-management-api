<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('task', [TaskController::class, 'index']);
Route::post('task/create', [TaskController::class, 'store'])->middleware('auth:api');
Route::post('task/assign/{id}', [TaskController::class, 'assign'])->middleware('auth:api');
Route::post('task/status/{id}', [TaskController::class, 'update'])->middleware('auth:api');
Route::get('tasks/', [TaskController::class, 'filter'])->middleware('auth:api');
Route::post('assign_role', [RoleAndPermissionController::class, 'assignRole'])->middleware('auth:api');
