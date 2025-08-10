<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\User;
use App\Http\Resources\TasksResource;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    /**
     * Get all tasks (GET)
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TasksResource::collection(Tasks::all());
    }

    /**
     * Create new task (POST)
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse|TasksResource
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse|TasksResource
    {
        $validatedData = $request->validate([
            "title" => ["required", "string", "max:100"],
            "description" => ["nullable", "string"],
            "creator_id" => ["required", "integer"],
            "executor_id" => ["required", "integer"],
            "end_date" => ["required", "date"]
        ]);

        //проверяем существование пользователей
        $creator = User::where("id", $validatedData["creator_id"])->first();
        if (!$creator) {
            return response()->json([
                "error" => "Пользователь - создатель не найден"
            ], 422);
        }

        $executor = User::where("id", $validatedData["executor_id"])->first();
        if (!$executor) {
            return response()->json([
                "error" => "Пользователь - исполнитель не найден"
            ], 422);
        }

        $newTask = new Tasks($validatedData);
        if ($newTask->save()) {
            return response()->json($newTask, 201);
        } else {
            return response()->json([
                "error" => "Произошла ошибка при создании задачи"
            ], 500);
        }
    }


    /**
     * Get the specified resource (GET)
     * 
     * @param string $id
     * 
     * @return \Illuminate\Http\JsonResponse|TasksResource
     */
    public function show(string $id): \Illuminate\Http\JsonResponse|TasksResource
    {
        $taskInfo = Tasks::where("id", $id)->first();

        if (!$taskInfo) {
            return response()->json([
                "error" => "Задача не найдена"
            ], 404);
        }

        return new TasksResource($taskInfo);
    }


    /**
     * Update the specified resource in storage (PUT/PATCH)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|TasksResource
     */
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse|TasksResource
    {
        $taskInfo = Tasks::where("id", $id)->first();
        if (!$taskInfo) {
            return response()->json([
                "error" => "Задача не найдена"
            ], 404);
        } else {
            $validatedData = $request->validate([
                "title" => ["sometimes", "required", "string", "max:100"],
                "description" => ["sometimes", "string", "nullable"],
                "creator_id" => ["sometimes", "required", "integer"],
                "executor_id" => ["sometimes", "required", "integer"],
                "end_date" => ["sometimes", "required", "date"],
                "is_done" => ["sometimes", "required", "boolean"],
            ]);
            $taskInfo->update($validatedData);
            return response()->json($taskInfo, 200);
        }
    }


    /**
     * Remove the specified resource from storage (DELETE)
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse|TasksResource
    {
        $taskInfo = Tasks::where("id", $id)->first();
        if (!$taskInfo) {
            return response()->json([
                "error" => "Задача не найдена"
            ], 404);
        } else {
            $taskInfo->delete();
            return response()->json(null, 204);
        }
    }
}
