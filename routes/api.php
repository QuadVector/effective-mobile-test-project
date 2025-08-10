<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

Route::apiResource('tasks', TasksController::class);
