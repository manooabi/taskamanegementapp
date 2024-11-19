<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve priority filter if provided
        $priority = $request->query('priority'); 
        
        // Retrieve sort direction for due_date
        $sortDirection = $request->query('sort', 'asc'); 
        
        // Query tasks with optional filtering and sorting
        $tasks = Task::when($priority, function ($query, $priority) {
                return $query->where('priority', $priority);
            })
            ->orderBy('due_date', $sortDirection)
            ->get();
        
        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|in:High,Medium,Low',
                'is_completed' => 'boolean',
                'is_paid' => 'boolean',
            ]);
    
            // Create a new task
            $task = Task::create([
                'user_id' => $validatedData['user_id'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'due_date' => $validatedData['due_date'],
                'priority' => $validatedData['priority'],
                'is_completed' => $validatedData['is_completed'] ?? false, // Default false
                'is_paid' => $validatedData['is_paid'] ?? false, // Default false
            ]);
    
            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'data' => $task,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(), // Provide detailed validation errors
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the task by ID
        $task = Task::find($id);

        // If the task does not exist, return an error response
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:High,Medium,Low',
            'is_completed' => 'boolean',
            'is_paid' => 'boolean',
        ]);

        // Update the task with the validated data
        $task->update([
            'user_id' => $validatedData['user_id'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'due_date' => $validatedData['due_date'],
            'priority' => $validatedData['priority'],
            'is_completed' => $validatedData['is_completed'] ?? false, // Default false
            'is_paid' => $validatedData['is_paid'] ?? false, // Default false
        ]);

        // Return a success response with the updated task
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $task,
        ], 200);
    }
    public function destroy($id)
{
    // Find the task by ID
    $task = Task::find($id);

    // If task not found, return a 404 error
    if (!$task) {
        return response()->json([
            'success' => false,
            'message' => 'Task not found.'
        ], 404);
    }

    // Delete the task
    $task->delete();

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Task deleted successfully.'
    ], 200);
}
// TaskController.php
public function complete($id)
{
    // Find the task by ID
    $task = Task::find($id);

    // If task not found, return a 404 error
    if (!$task) {
        return response()->json([
            'success' => false,
            'message' => 'Task not found.'
        ], 404);
    }

    // Mark the task as completed
    $task->is_completed = true;
    $task->save();

    $userName = $task->user ? $task->user->name : 'User';

    $data = [
        'name' => $userName,
        'taskTitle' => $task->title
    ];

    Mail::send('mail', $data, function ($message) {
        $message->to('abc@gmail.com', 'Task Management')
                ->subject('Task Completed');
        $message->from('xyz@gmail.com', 'Manoo');
    });
    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Task marked as completed.',
        'data' => $task
    ], 200);
}
}