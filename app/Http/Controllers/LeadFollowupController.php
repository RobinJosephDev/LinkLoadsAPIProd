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
        return $this->lead_follow_up->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'lead_no' => 'required',
            'lead_date' => 'nullable|date',
            'customer_name' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'postal_code' => 'nullable',
            'unit_no' => 'nullable',
            'lead_type' => 'nullable',
            'contact_person' => 'nullable',
            'notes' => 'nullable',
            'remarks' => 'nullable',
            'equipment' => 'nullable',
            'next_follow_up_date' => 'nullable|date',
            'lead_status' => 'required',
            'products' => 'nullable|string', 
            'contacts' => 'nullable|string', 
        ]);
    
        // Store the data
        return $this->lead_follow_up->create($validatedData);
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
        // Attempt to find the lead by its ID
        $lead_follow_up = $this->lead_follow_up->find($id);
    
        // If the lead is not found, return a 404 response with an error message
        if (!$lead_follow_up) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
    
        // Get the data to update from the request
        $followupData = $request->all();
    
        // Handle `contacts` field update if necessary
        if (isset($followupData['contacts']) && is_array($followupData['contacts'])) {
            $followupData['contacts'] = json_encode($followupData['contacts']);
        }
    
        // Perform the update
        $lead_follow_up->update($followupData);
    
        // Return the updated lead
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
        // Get the current logged-in user ID
        $userId = Auth::id();

        // Perform the join query to get records from lead_follow_up and leads tables
        $results = DB::table('lead_follow_up')
            ->join('leads', 'lead_follow_up.lead_no', '=', 'leads.lead_no')
            ->where('leads.assigned_to', '=', $userId)  // Filter by assigned_to field of leads
            ->select('lead_follow_up.*', 'leads.*') // Select the fields you need
            ->get();

        // Return the results (you can return this as JSON, or pass to a view)
        return response()->json($results);
    }
}
