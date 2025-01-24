<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeadController extends Controller
{
    public function getCachedData()
    {
        // Attempt to retrieve data from the cache
        $value = Cache::get('key');

        // If not found in cache, fetch from database or other source
        if (!$value) {
            $value = 'default value'; // Replace this with actual data retrieval logic

            // Store in cache for future requests
            Cache::put('key', $value, now()->addMinutes(10));
        }

        return response()->json(['value' => $value]);
    }

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
        return $this->lead->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $leadData = $request->all();

        // Handle `contacts` field to ensure it's correctly stored as JSON
        if (isset($leadData['contacts']) && is_array($leadData['contacts'])) {
            $leadData['contacts'] = json_encode($leadData['contacts']);
        }

        return $this->lead->create($leadData);
    }

    /**
     * Display the specified resource.
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
        // Attempt to find the lead by its ID
        $lead = $this->lead->find($id);

        // If the lead is not found, return a 404 response with an error message
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        // Get the data to update from the request
        $leadData = $request->all();

        // Handle `contacts` field update if necessary
        if (isset($leadData['contacts']) && is_array($leadData['contacts'])) {
            $leadData['contacts'] = json_encode($leadData['contacts']);
        }

        // Perform the update
        $lead->update($leadData);

        // Return the updated lead
        return response()->json($lead);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = $this->lead->find($id);
        return $lead->delete();
    }
}
