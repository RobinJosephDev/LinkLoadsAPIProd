<?php

namespace App\Http\Controllers;

use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validatedData = $this->validateFollowup($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $followupData = $validatedData->validated();
        $lead_follow_up = $this->lead_follow_up->create($followupData);

        return response()->json($lead_follow_up, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->lead_follow_up->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead_follow_up = $this->lead_follow_up->find($id);

        if (!$lead_follow_up) {
            return response()->json(['error' => 'Followup not found'], 404);
        }

        $validatedData = $this->validateFollowup($request, $lead_follow_up->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $lead_follow_up->update($validatedData->validated());

        return response()->json($lead_follow_up);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead_follow_up = $this->lead_follow_up->find($id);
        if (!$lead_follow_up) {
            return response()->json(['error' => 'Followup not found'], 404);
        }

        $lead_follow_up->delete();
        return response()->json(['message' => 'Followup deleted successfully'], 200);
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

    private function validateFollowup(Request $request, $id = null)
    {
        return Validator::make($request->all(), [

            //Lead Info
            'lead_no' =>  'required|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]+$/',
            'lead_date' => 'required|date',
            'customer_name' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'email' => 'nullable|max:255|email',
            'lead_type' => 'required|string|in:AB,BC,BDS,CA,DPD MAGMA,MB,ON,Super Leads,TBAB,USA',
            'lead_status' => 'required|string|in:New,In Progress,Completed,On Hold,Lost',

            //Address Details
            'address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'unit_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'postal_code' => 'nullable|regex:/^[a-zA-Z0-9\s]{0,20}$/',

            //Additional Information
            'contact_person' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'next_follow_up_date' => 'nullable|date',
            'equipment' => 'nullable|string|in:Van,Reefer,Flatbed,Triaxle,Maxi,Btrain,Roll tite',
            'notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            //Contacts
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'contacts.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.email' => 'nullable|max:255|email',

            //Products
            'products' => 'nullable|array',
            'products.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'products.*.quantity' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
        ]);
    }
}
