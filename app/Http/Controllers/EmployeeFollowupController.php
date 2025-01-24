<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EmployeeFollowupController extends Controller
{
    public function index()
    {
        Log::info('Lead Followup Employee Route Hit');

        // Get the currently authenticated user
        $user = Auth::user();

        if (!$user) {
            Log::error('User not authenticated');
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        Log::info('Authenticated user:', ['user' => $user]);

        // Query to fetch lead follow-ups with LEFT JOIN on leads
        $leadFollowups = DB::table('lead_follow_up')
            ->leftJoin('leads', 'lead_follow_up.lead_no', '=', 'leads.lead_no')
            ->where('leads.assigned_to', $user->name)
            ->select('lead_follow_up.*');

        // Log the SQL query for debugging
        Log::info('SQL Query:', ['query' => $leadFollowups->toSql()]);

        $leadFollowups = $leadFollowups->get();

        // If no data found, log and return 404
        if ($leadFollowups->isEmpty()) {
            Log::info('No lead follow-ups found for the current user.');
            return response()->json(['message' => 'No lead follow-ups found'], 404);
        }

        // Return the lead follow-ups as a JSON response
        return response()->json($leadFollowups);
    }
}
