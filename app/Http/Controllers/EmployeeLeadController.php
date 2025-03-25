<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $currentUser = Auth::user();
        if (!$currentUser) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 401);
        }
        $lead = Lead::where('assigned_to', $currentUser->name)->find($id);

        if (!$lead) {
            return response()->json([
                'message' => 'Lead not found or not assigned to you'
            ], 404);
        }

        $validatedData = $this->validateLead($request, $lead->id);
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        $lead->update($validatedData->validated());
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
