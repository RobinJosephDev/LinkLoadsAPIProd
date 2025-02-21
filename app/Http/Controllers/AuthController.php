<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

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
        // Validate the incoming data with custom password validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/',  
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#^]/',  
            ],
            'role' => 'required|string|in:admin,employee,carrier,customer',
            'emp_code' => 'nullable|string|unique:users,emp_code|max:255',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.min' => 'Password must be at least 12 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ],
   );
    
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
                'emp_code' => $validated['emp_code'],
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
        // Validate username and password
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        // Check if MFA is enabled
        if ($user->is_mfa_enabled) {
            return response()->json([
                'mfa_required' => true, // Indicates that MFA is required
                'mfa_secret' => $user->mfa_secret, // You can return this if needed for the frontend to generate QR code
            ]);
        }
    
        // If no MFA is required, generate token
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => $user
        ]);
    }
    
    // Verify MFA token
    public function verifyMfa(Request $request)
    {
        $request->validate([
            'mfa_code' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ]);
    
        $user = User::find($request->user_id);
        $google2fa = new Google2FA();
    
        // Verify the MFA code
        if (!$google2fa->verifyKey($user->mfa_secret, $request->mfa_code)) {
            return response()->json(['error' => 'Invalid MFA code'], 400);
        }
    
        // Generate token after MFA success
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'message' => 'MFA verified successfully!',
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
