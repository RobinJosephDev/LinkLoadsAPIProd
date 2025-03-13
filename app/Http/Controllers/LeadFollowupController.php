<?php

namespace App\Http\Controllers;

use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadFollowupController extends Controller
{
    protected $lead_follow_up;

    public function __construct()
    {
        $this->lead_follow_up = new LeadFollowup();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->lead_follow_up->orderBy('created_at', 'desc')->get());
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Store the data
        return $this->lead_follow_up->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->lead_follow_up->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead_follow_up = $this->lead_follow_up->find($id);

        if (!$lead_follow_up) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $lead_follow_up->update($request->all());

        return response()->json($lead_follow_up);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead_follow_up = $this->lead_follow_up->findOrFail($id);
        $lead_follow_up->delete();

        return response()->json(null, 204);
    }

    /**
     * Get lead follow-ups for the currently logged-in user.
     */
    public function getLeadFollowupsForUser()
    {
        $userId = Auth::id();

        $results = DB::table('lead_follow_up')
            ->join('leads', 'lead_follow_up.lead_no', '=', 'leads.lead_no')
            ->where('leads.assigned_to', '=', $userId)
            ->select('lead_follow_up.*', 'leads.*')
            ->get();

        return response()->json($results);
    }
}
