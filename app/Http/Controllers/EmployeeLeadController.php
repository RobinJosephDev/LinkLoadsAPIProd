<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeLeadController extends Controller
{
    /**
     * Fetch leads assigned to the current employee (user).
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is authenticated
        if (!$currentUser) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 401);
        }
        Log::info('Authenticated user:', ['user' => $currentUser]);
        // Fetch leads assigned to the current user
        $leads = Lead::where('assigned_to', $currentUser->name)
            ->orderBy('lead_date', 'desc')
            ->get();

        // Return the leads data
        return response()->json($leads);
    }

    /**
     * Fetch a specific lead by ID (assigned to the current employee).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is authenticated
        if (!$currentUser) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 401);
        }

        // Find the lead by ID and check if it's assigned to the current user
        $lead = Lead::where('assigned_to', $currentUser->name)
            ->find($id);

        // Check if lead exists and belongs to the current user
        if (!$lead) {
            return response()->json([
                'message' => 'Lead not found or not assigned to you'
            ], 404);
        }

        // Return the lead data
        return response()->json($lead);
    }

    /**
     * Update a lead (assigned to the current employee).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is authenticated
        if (!$currentUser) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 401);
        }

        // Find the lead by ID
        $lead = Lead::where('assigned_to', $currentUser->name)->find($id);

        if (!$lead) {
            return response()->json([
                'message' => 'Lead not found or not assigned to you'
            ], 404);
        }

        // Validate incoming request data (you can customize validation as needed)
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'lead_status' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // Update the lead with the validated data
        $lead->update($validatedData);

        // Return the updated lead data
        return response()->json($lead);
    }

    /**
     * Delete a lead (assigned to the current employee).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is authenticated
        if (!$currentUser) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 401);
        }

        // Find the lead by ID
        $lead = Lead::where('assigned_to', $currentUser->name)->find($id);

        if (!$lead) {
            return response()->json([
                'message' => 'Lead not found or not assigned to you'
            ], 404);
        }

        // Delete the lead
        $lead->delete();

        // Return success message
        return response()->json([
            'message' => 'Lead deleted successfully'
        ]);
    }
}
