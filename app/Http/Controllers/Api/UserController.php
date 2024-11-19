<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
{
    // Fetch users with optional pagination
    $users = User::paginate(10); // Change 10 to any desired pagination size

    return response()->json([
        'success' => true,
        'message' => 'Users retrieved successfully.',
        'data' => $users,
    ], 200);
}

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed', // Use password confirmation
            ]);
    
            // Hash the password
            $validatedData['password'] = bcrypt($validatedData['password']);
    
            // Create a new user
            $user = User::create($validatedData);
    
            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user,
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
}
