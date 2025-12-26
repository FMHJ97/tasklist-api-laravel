<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all tasks for user.
        $tasks = Task::whereUserId(1)->latest()->get();

        // Return the tasks as a collection of TaskResource.
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data.
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create a new Task.
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'), // Null if not provided (optional)
            'user_id' => 1, // User Dummy
        ]);

        // Return the created task using TaskResource.
        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201); // HTTP 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the task by ID.
        $task = Task::whereUserId(1)->findOrFail($id); // User Dummy

        // Return the task using TaskResource.
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate incoming request data.
        // Sometimes means the field is optional, but if present, it must meet the validation rules.
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        // Find the task by ID.
        $task = Task::whereUserId(1)->findOrFail($id); // User Dummy

        // Update the task with validated data.
        // Only update the fields that are present in the request.
        $task->update($request->only(['title', 'description', 'completed']));

        // Return the updated task using TaskResource.
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the task by ID.
        $task = Task::whereUserId(1)->findOrFail($id); // User Dummy

        // Delete the task.
        $task->delete();

        // Return a 204 No Content response.
        return response()->noContent();
    }
}
