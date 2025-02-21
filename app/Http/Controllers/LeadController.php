<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeadController extends Controller
{
    public function getCachedData()
    {
        $value = Cache::remember('key', now()->addMinutes(10), function () {
            return 'default value';
        });

        return response()->json(['value' => $value]);
    }


    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => $this->lead->all()]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'lead_no' => 'required|string|max:50',
            'lead_date' => 'required|nullable|date',
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'unit_no' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'lead_type' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'lead_status' => 'required|string|max:50',
            'follow_up_date' => 'nullable|date',
            'equipment_type' => 'nullable|string|max:100',
            'assigned_to' => 'nullable|string|max:50',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:50',
            'contacts.*.email' => 'nullable|email|max:255',
        ]);

        // Sanitizing the validated data
        $validatedData['lead_no'] = trim($validatedData['lead_no']);
        $validatedData['customer_name'] = isset($validatedData['customer_name']) ? trim($validatedData['customer_name']) : null;
        $validatedData['phone'] = isset($validatedData['phone']) ? trim($validatedData['phone']) : null;
        $validatedData['email'] = isset($validatedData['email']) ? filter_var(trim($validatedData['email']), FILTER_SANITIZE_EMAIL) : null;
        $validatedData['website'] = isset($validatedData['website']) ? filter_var(trim($validatedData['website']), FILTER_SANITIZE_URL) : null;
        $validatedData['address'] = isset($validatedData['address']) ? trim($validatedData['address']) : null;
        $validatedData['unit_no'] = isset($validatedData['unit_no']) ? trim($validatedData['unit_no']) : null;
        $validatedData['city'] = isset($validatedData['city']) ? trim($validatedData['city']) : null;
        $validatedData['state'] = isset($validatedData['state']) ? trim($validatedData['state']) : null;
        $validatedData['country'] = isset($validatedData['country']) ? trim($validatedData['country']) : null;
        $validatedData['postal_code'] = isset($validatedData['postal_code']) ? trim($validatedData['postal_code']) : null;
        $validatedData['lead_type'] = trim($validatedData['lead_type']);
        $validatedData['contact_person'] = isset($validatedData['contact_person']) ? trim($validatedData['contact_person']) : null;
        $validatedData['notes'] = isset($validatedData['notes']) ? trim($validatedData['notes']) : null;
        $validatedData['lead_status'] = trim($validatedData['lead_status']);
        $validatedData['follow_up_date'] = isset($validatedData['follow_up_date']) ? trim($validatedData['follow_up_date']) : null;
        $validatedData['equipment_type'] = isset($validatedData['equipment_type']) ? trim($validatedData['equipment_type']) : null;

        // Sanitizing contacts array if present
        if (isset($validatedData['contacts']) && is_array($validatedData['contacts'])) {
            $validatedData['contacts'] = array_map(function ($contact) {
                $contact['name'] = isset($contact['name']) ? trim($contact['name']) : null;
                $contact['phone'] = isset($contact['phone']) ? trim($contact['phone']) : null;
                $contact['email'] = isset($contact['email']) ? filter_var(trim($contact['email']), FILTER_SANITIZE_EMAIL) : null;
                return $contact;
            }, $validatedData['contacts']);
        }

        // Convert contacts array to JSON before storing
        if (isset($validatedData['contacts'])) {
            $validatedData['contacts'] = json_encode($validatedData['contacts']);
        }

        // Save the lead
        return $this->lead->create($validatedData);
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        if ($id === 'cached') {
            return response()->json(['value' => Cache::get('key', 'default value')]);
        }

        return Lead::findOrFail($id);
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

        // Validate incoming data
        $validatedData = $request->validate([
            'lead_no' => 'required|string|max:50',
            'lead_date' => 'required|date',
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'unit_no' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'lead_type' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'lead_status' => 'required|string|max:50',
            'follow_up_date' => 'nullable|date',
            'equipment_type' => 'nullable|string|max:100',
            'assigned_to' => 'nullable|string|max:50',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:50',
            'contacts.*.email' => 'nullable|email|max:255',

        ]);

        // Sanitizing the validated data
        $validatedData['lead_no'] = trim($validatedData['lead_no']);
        $validatedData['customer_name'] = isset($validatedData['customer_name']) ? trim($validatedData['customer_name']) : null;
        $validatedData['phone'] = isset($validatedData['phone']) ? trim($validatedData['phone']) : null;
        $validatedData['email'] = isset($validatedData['email']) ? filter_var(trim($validatedData['email']), FILTER_SANITIZE_EMAIL) : null;
        $validatedData['website'] = isset($validatedData['website']) ? filter_var(trim($validatedData['website']), FILTER_SANITIZE_URL) : null;
        $validatedData['address'] = isset($validatedData['address']) ? trim($validatedData['address']) : null;
        $validatedData['unit_no'] = isset($validatedData['unit_no']) ? trim($validatedData['unit_no']) : null;
        $validatedData['city'] = isset($validatedData['city']) ? trim($validatedData['city']) : null;
        $validatedData['state'] = isset($validatedData['state']) ? trim($validatedData['state']) : null;
        $validatedData['country'] = isset($validatedData['country']) ? trim($validatedData['country']) : null;
        $validatedData['postal_code'] = isset($validatedData['postal_code']) ? trim($validatedData['postal_code']) : null;
        $validatedData['lead_type'] = trim($validatedData['lead_type']);
        $validatedData['contact_person'] = isset($validatedData['contact_person']) ? trim($validatedData['contact_person']) : null;
        $validatedData['notes'] = isset($validatedData['notes']) ? trim($validatedData['notes']) : null;
        $validatedData['lead_status'] = trim($validatedData['lead_status']);
        $validatedData['follow_up_date'] = isset($validatedData['follow_up_date']) ? trim($validatedData['follow_up_date']) : null;
        $validatedData['equipment_type'] = isset($validatedData['equipment_type']) ? trim($validatedData['equipment_type']) : null;

        // Sanitizing contacts array if present
        if (isset($validatedData['contacts']) && is_array($validatedData['contacts'])) {
            $validatedData['contacts'] = array_map(function ($contact) {
                $contact['name'] = isset($contact['name']) ? trim($contact['name']) : null;
                $contact['phone'] = isset($contact['phone']) ? trim($contact['phone']) : null;
                $contact['email'] = isset($contact['email']) ? filter_var(trim($contact['email']), FILTER_SANITIZE_EMAIL) : null;
                return $contact;
            }, $validatedData['contacts']);
        }

        // Convert contacts array to JSON before storing
        if (isset($validatedData['contacts'])) {
            $validatedData['contacts'] = json_encode($validatedData['contacts']);
        }

        // Update the lead
        $lead->update($validatedData);

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
}
