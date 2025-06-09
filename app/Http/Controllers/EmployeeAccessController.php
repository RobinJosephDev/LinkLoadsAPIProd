<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAccess;
use Illuminate\Http\Request;

class EmployeeAccessController extends Controller
{
    public function index()
    {
        return EmployeeAccess::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leads' => 'boolean',
            'followup' => 'boolean',
            'quotes' => 'boolean',
            'leads_quotes' => 'boolean',
            'customers' => 'boolean',
            'orders' => 'boolean',
            'carriers' => 'boolean',
            'vendors' => 'boolean',
            'brokers' => 'boolean',
            'users' => 'boolean',
        ]);

        $access = EmployeeAccess::create($validated);
        return response()->json($access, 201);
    }

    public function show($id)
    {
        return EmployeeAccess::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $access = EmployeeAccess::findOrFail($id);

        $validated = $request->validate([
            'leads' => 'boolean',
            'followup' => 'boolean',
            'quotes' => 'boolean',
            'leads_quotes' => 'boolean',
            'customers' => 'boolean',
            'orders' => 'boolean',
            'carriers' => 'boolean',
            'vendors' => 'boolean',
            'brokers' => 'boolean',
            'users' => 'boolean',
        ]);

        $access->update($validated);
        return response()->json($access, 200);
    }

    public function destroy($id)
    {
        $access = EmployeeAccess::findOrFail($id);
        $access->delete();
        return response()->json(null, 204);
    }
}
