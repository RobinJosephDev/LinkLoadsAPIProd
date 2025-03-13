<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    // Get all users
    public function index()
    {
        return response()->json($this->user->orderBy('created_at', 'desc')->get());
    }

    // Get user by ID
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user);
    }

    // Delete user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
