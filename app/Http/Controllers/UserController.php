<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        return response()->json($this->user->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateUser($request);
    
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
    
        $userData = $validatedData->validated();
        $userData['password'] = bcrypt($userData['password']);
    
        $user = $this->user->create($userData);
    
        return response()->json($user, 201);
    }
    

    public function show(string $id)
    {
        return $this->user->find($id);
    }

    public function update(Request $request, string $id)
    {
        $user = $this->user->find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $validatedData = $this->validateUser($request, $user->id);
    
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
    
        $userData = $validatedData->validated();
    
        if (!empty($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']); 
        }
    
        $user->update($userData);
    
        return response()->json($user);
    }
    

    public function destroy(string $id)
    {
        $user = $this->user->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    private function validateUser(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'email' => 'required|string|email|max:50',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:200',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirmed'
            ],
            'password_confirmation' => 'required|string|max:200',
            'emp_code' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'role' => 'required|string|in:Admin,Employee,Carrier,Customer'
        ]);
    }
}
