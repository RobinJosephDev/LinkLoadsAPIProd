<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

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
        $validated = $request->validate(
            [
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
            ],
            [
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
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = 'login_attempts:' . $request->ip();
        $maxAttempts = 12; // Permanent block threshold
        $lockoutSeconds = 60; // Temporary lockout duration

        // ✅ Check if the user is permanently blocked
        if (RateLimiter::attempts($throttleKey) >= $maxAttempts) {
            return response()->json([
                'error' => 'You have been permanently blocked due to too many failed login attempts.'
            ], Response::HTTP_FORBIDDEN); // 403 Forbidden
        }

        // ✅ Check if the user is temporarily blocked (rate limit exceeded)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'error' => 'Too many login attempts. Please try again in ' . RateLimiter::availableIn($throttleKey) . ' seconds.'
            ], Response::HTTP_TOO_MANY_REQUESTS); // 429 Too Many Requests
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, $lockoutSeconds);

            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // ✅ Reset throttle count on successful login
        RateLimiter::clear($throttleKey);

        if ($user->is_mfa_enabled) {
            return response()->json([
                'mfa_required' => true,
                'mfa_secret' => $user->mfa_secret,
            ]);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => $user
        ]);
        Log::info('Login attempts: ' . RateLimiter::attempts($throttleKey));
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
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
    
        return response()->json(['message' => 'Logged out successfully']);
    }
    
}
