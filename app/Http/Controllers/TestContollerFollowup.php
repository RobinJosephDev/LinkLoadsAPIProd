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
        // Validate input data
        $validatedData = $request->validate([
            'lead_no' => 'required|string|max:50',
            'lead_date' => 'nullable|date',
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'unit_no' => 'nullable|string|max:50',
            'lead_type' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
            'equipment' => 'nullable|string|max:100',
            'next_follow_up_date' => 'nullable|date',
            'lead_status' => 'required|string|max:50',
            'products' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:50',
            'contacts.*.email' => 'nullable|email|max:255',
        ]);

        // Sanitize the validated data
        $validatedData = $this->sanitizeData($validatedData);

        // Convert contacts array to JSON before storing
        if (isset($validatedData['contacts'])) {
            $validatedData['contacts'] = json_encode($validatedData['contacts']);
        }

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
        $lead_follow_up = $this->lead_follow_up->find($id);

        if (!$lead_follow_up) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        // Validate input data
        $validatedData = $request->validate([
            'lead_no' => 'required|string|max:50',
            'lead_date' => 'nullable|date',
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'unit_no' => 'nullable|string|max:50',
            'lead_type' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
            'equipment' => 'nullable|string|max:100',
            'next_follow_up_date' => 'nullable|date',
            'lead_status' => 'required|string|max:50',
            'products' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:50',
            'contacts.*.email' => 'nullable|email|max:255',
        ]);

        // Sanitize the validated data
        $validatedData = $this->sanitizeData($validatedData);

        // Convert contacts array to JSON before updating
        if (isset($validatedData['contacts'])) {
            $validatedData['contacts'] = json_encode($validatedData['contacts']);
        }

        $lead_follow_up->update($validatedData);

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

    /**
     * Sanitize validated data.
     */
    private function sanitizeData(array $data): array
    {
        $fieldsToSanitize = [
            'lead_no',
            'customer_name',
            'phone',
            'email',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'unit_no',
            'lead_type',
            'contact_person',
            'notes',
            'remarks',
            'equipment',
        ];

        foreach ($fieldsToSanitize as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim($data[$field]);
                if ($field === 'email') {
                    $data[$field] = filter_var($data[$field], FILTER_SANITIZE_EMAIL);
                }
            }
        }

        if (isset($data['contacts']) && is_array($data['contacts'])) {
            $data['contacts'] = array_map(function ($contact) {
                $contact['name'] = isset($contact['name']) ? trim($contact['name']) : null;
                $contact['phone'] = isset($contact['phone']) ? trim($contact['phone']) : null;
                $contact['email'] = isset($contact['email']) ? filter_var(trim($contact['email']), FILTER_SANITIZE_EMAIL) : null;
                return $contact;
            }, $data['contacts']);
        }

        return $data;
    }
}
