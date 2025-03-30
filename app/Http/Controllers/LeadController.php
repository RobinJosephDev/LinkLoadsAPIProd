<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    protected $lead;

    public function __construct()
    {
        $this->lead = new Lead();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->lead->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateLead($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $leadData = $validatedData->validated();
        $lead = $this->lead->create($leadData);

        return response()->json($lead, 201);
    }

    /**
     * Display the specified lead.
     */
    public function show(string $id)
    {
        return $this->lead->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = $this->lead->find($id);

        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $validatedData = $this->validateLead($request, $lead->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $lead->update($validatedData->validated());

        return response()->json($lead);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = $this->lead->find($id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $lead->delete();
        return response()->json(['message' => 'Lead deleted successfully'], 200);
    }

    /**
     * Validate lead input data.
     */
    private function validateLead(Request $request, $id = null)
    {
        return Validator::make($request->all(), [

            //Lead Details
            'lead_no' =>  'required|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]+$/',
            'lead_date' => 'required|date',
            'customer_name' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'email' => 'nullable|max:255|email',
            'website' => 'nullable|url|max:255',
            'lead_type' => 'required|string|in:AB,BC,BDS,CA,DPD MAGMA,MB,ON,Super Leads,TBAB,USA',
            'lead_status' => 'required|string|in:Prospect,Lanes discussed,Prod/Equip noted,E-mail sent,Portal registration,Quotations,Fob/Have broker,VM/No answer,Diff Dept.,No reply,Not Int.,Asset based',

            //Address Details
            'address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'unit_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'postal_code' => 'nullable|regex:/^[a-zA-Z0-9\s]{0,20}$/',

            //Additional Information
            'contact_person' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'follow_up_date' => 'nullable|date',
            'equipment_type' => 'nullable|string|in:Van,Reefer,Flatbed,Triaxle,Maxi,Btrain,Roll tite',
            'assigned_to' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            //Contacts
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'contacts.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.email' => 'nullable|max:255|email',
        ]);
    }
}
