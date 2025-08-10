<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\User;
use App\Http\Resources\TasksResource;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Получить список всех задач (GET)
     */
    public function index()
    {
        return TasksResource::collection(Tasks::all());
    }

    /**
     * Создать новую задачу (POST)
     */
    public function store(Request $request)
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
     * Отобразить информацию по текущий задаче (GET)
     */
    public function show(string $id)
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
     * Обновить информацию по конкретной задаче (PUT)
     */
    public function update(Request $request, string $id)
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
     * Удалить конкретную задачу (DELETE)
     */
    public function destroy(string $id)
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
