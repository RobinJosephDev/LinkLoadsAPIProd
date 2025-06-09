<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        DB::beginTransaction();
        try {
            $user = $this->user->create($userData);

            // If role is Employee, insert employee_access record
            if ($user->role === 'Employee') {
                EmployeeAccess::create([
                    'id' => $user->id,
                    'user_id' => $user->id,
                    'permissions' => json_encode($request->input('permissions', [])), // array of permissions
                ]);
            }

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create user', 'message' => $e->getMessage()], 500);
        }
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

        DB::beginTransaction();
        try {
            $user->update($userData);

            // Update or create employee_access if role is Employee
            if ($user->role === 'Employee') {
                $permissions = json_encode($request->input('permissions', []));

                EmployeeAccess::updateOrCreate(
                    ['user_id' => $user->id],
                    ['permissions' => $permissions]
                );
            } else {
                // If role changed from Employee to something else, remove access
                EmployeeAccess::where('user_id', $user->id)->delete();
            }

            DB::commit();
            return response()->json($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update user', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $user = $this->user->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Delete employee access if exists
            EmployeeAccess::where('user_id', $user->id)->delete();

            $user->delete();

            DB::commit();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete user', 'message' => $e->getMessage()], 500);
        }
    }

    private function validateUser(Request $request, $id = null)
    {
        $passwordRule = $id ? 'nullable' : 'required';

        return Validator::make($request->all(), [
            'name' => 'required|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'email' => 'required|string|email|max:50',
            'password' => [
                $passwordRule,
                'string',
                'min:8',
                'max:200',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirmed'
            ],
            'password_confirmation' => $passwordRule === 'required' ? 'required|string|max:200' : 'nullable|string|max:200',
            'emp_code' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'role' => 'required|string|in:Admin,Employee,Carrier,Customer',
            'permissions' => 'array', // optional, only for Employee role
            'permissions.*' => 'string',
        ]);
    }
}
