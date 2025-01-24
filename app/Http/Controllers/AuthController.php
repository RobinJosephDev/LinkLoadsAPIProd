<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user and return the token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',  // Ensure password_confirmation field is provided
            'role' => 'required|string|in:admin,employee,carrier,customer', // Specific allowed roles
  
        ]);

        // Log the validated data to verify what is coming to the server
        Log::info('User registration data:', $validated);

        try {
            // Create the user in the database
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),  // Hash password before storing
                'role' => $validated['role'],
  
            ]);

            // Generate a new token for the user
            $token = $user->createToken('API Token')->plainTextToken;

            // Return success response with token
            return response()->json([
                'message' => 'User created successfully!',
                'token' => $token,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('User registration failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Failed to register user. Please try again later.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log the user in and return the token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        Log::info('Login request received', $request->all());
    
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        Log::info('Validation passed');
    
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            Log::warning('User not found', ['username' => $request->username]);
            throw ValidationException::withMessages([
                'username' => ['User not found.'],
            ]);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Invalid password for user', ['username' => $request->username]);
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        Log::info('User authenticated', ['user' => $user]);
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => $user
        ]);
    }
    
    

    /**
     * Logout the user and revoke tokens.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully']);
    }
}
