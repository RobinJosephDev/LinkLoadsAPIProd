<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    /**
     * Get all customers.
     */
    public function index()
    {
        return response()->json($this->customer->orderBy('created_at', 'desc')->get());
    }


    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'cust_type' => 'nullable|string|max:255',
            'cust_name' => 'required|string|max:100',
            'cust_ref_no' => 'nullable|string|max:100',
            'cust_website' => 'nullable|string|max:255',
            'cust_email' => 'nullable|email|max:100',
            'cust_contact_no' => 'nullable|string|max:255',
            'cust_contact_no_ext' => 'nullable|string|max:100',
            'cust_tax_id' => 'nullable|string|max:255',
            'cust_primary_address' => 'nullable|string|max:255',
            'cust_primary_city' => 'nullable|string|max:255',
            'cust_primary_state' => 'nullable|string|max:255',
            'cust_primary_country' => 'nullable|string|max:255',
            'cust_primary_postal' => 'nullable|string|max:255',
            'cust_primary_unit_no' => 'nullable|string|max:255',
            'cust_mailing_address' => 'nullable|string|max:255',
            'cust_mailing_city' => 'nullable|string|max:255',
            'cust_mailing_state' => 'nullable|string|max:255',
            'cust_mailing_country' => 'nullable|string|max:255',
            'cust_mailing_postal' => 'nullable|string|max:255',
            'cust_mailing_unit_no' => 'nullable|string|max:255',
            'cust_ap_name' => 'nullable|string|max:255',
            'cust_ap_address' => 'nullable|string|max:255',
            'cust_ap_city' => 'nullable|string|max:255',
            'cust_ap_state' => 'nullable|string|max:255',
            'cust_ap_country' => 'nullable|string|max:255',
            'cust_ap_postal' => 'nullable|string|max:255',
            'cust_ap_unit_no' => 'nullable|string|max:255',
            'cust_ap_email' => 'nullable|email|max:100',
            'cust_ap_phone' => 'nullable|string|max:255',
            'cust_ap_phone_ext' => 'nullable|string|max:255',
            'cust_ap_fax' => 'nullable|string|max:255',
            'cust_broker_name' => 'nullable|string|max:255',
            'cust_bkp_notes' => 'nullable|string|max:255',
            'cust_bkspl_notes' => 'nullable|string|max:255',
            'cust_credit_status' => 'nullable|string|max:255',
            'cust_credit_mop' => 'nullable|string|max:255',
            'cust_credit_appd' => 'nullable|string|max:255',
            'cust_credit_expd' => 'nullable|string|max:255',
            'cust_credit_terms' => 'nullable|string|max:255',
            'cust_credit_limit' => 'nullable|string|max:255',
            'cust_credit_notes' => 'nullable|string|max:255',
            'cust_credit_application' => 'nullable|boolean',
            'cust_credit_currency' => 'nullable|string|max:255',
            'cust_sbk_agreement' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:10240',
            'cust_credit_agreement' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:10240',
            'cust_contact' => 'nullable|array',
            'cust_contact.*.name' => 'nullable|string|max:255',
            'cust_contact.*.phone' => 'nullable|string|max:255',
            'cust_contact.*.ext' => 'nullable|string|max:255',
            'cust_contact.*.email' => 'nullable|email|max:100',
            'cust_contact.*.fax' => 'nullable|string|max:255',
            'cust_contact.*.designation' => 'nullable|string|max:255',
            'cust_equipment' => 'nullable|array',
            'cust_equipment.*.equipment' => 'nullable|string|max:255',
        ]);

        // Sanitize all fields
        foreach ($validatedData as $key => &$value) {
            if (is_string($value)) {
                $value = trim($value); // Trim strings
            } elseif (is_array($value)) {
                foreach ($value as &$item) {
                    if (is_array($item)) {
                        foreach ($item as $subKey => &$subValue) {
                            if (is_string($subValue)) {
                                $subValue = trim($subValue); // Trim nested strings
                            }
                        }
                    } elseif (is_string($item)) {
                        $item = trim($item); // Trim strings in arrays
                    }
                }
            } elseif (is_numeric($value)) {
                $value = (float) $value; // Ensure numeric fields are cast properly
            }
        }

        // Handle file uploads (if any)
        if ($request->hasFile('cust_sbk_agreement')) {
            $validatedData['cust_sbk_agreement'] = $request->file('cust_sbk_agreement')->store('agreements');
        }

        if ($request->hasFile('cust_credit_agreement')) {
            $validatedData['cust_credit_agreement'] = $request->file('cust_credit_agreement')->store('agreements');
        }

        // Encode arrays into JSON for storage
        if (isset($validatedData['cust_contact']) && is_array($validatedData['cust_contact'])) {
            $validatedData['cust_contact'] = json_encode($validatedData['cust_contact']);
        }

        if (isset($validatedData['cust_equipment']) && is_array($validatedData['cust_equipment'])) {
            $validatedData['cust_equipment'] = json_encode($validatedData['cust_equipment']);
        }

        // Create new customer
        $customer = $this->customer->create($validatedData);

        return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
    }

    /**
     * Display a specific customer.
     */
    public function show(string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);  // Return 404 if customer not found
        }

        return response()->json($customer);
    }

    /**
     * Update a specific customer.
     */
    public function update(Request $request, string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'cust_type' => 'nullable|string|max:255',
            'cust_name' => 'required|string|max:100',
            'cust_ref_no' => 'nullable|string|max:100',
            'cust_website' => 'nullable|string|max:255',
            'cust_email' => 'nullable|email|max:100',
            'cust_contact_no' => 'nullable|string|max:255',
            'cust_contact_no_ext' => 'nullable|string|max:100',
            'cust_tax_id' => 'nullable|string|max:255',
            'cust_primary_address' => 'nullable|string|max:255',
            'cust_primary_city' => 'nullable|string|max:255',
            'cust_primary_state' => 'nullable|string|max:255',
            'cust_primary_country' => 'nullable|string|max:255',
            'cust_primary_postal' => 'nullable|string|max:255',
            'cust_primary_unit_no' => 'nullable|string|max:255',
            'cust_mailing_address' => 'nullable|string|max:255',
            'cust_mailing_city' => 'nullable|string|max:255',
            'cust_mailing_state' => 'nullable|string|max:255',
            'cust_mailing_country' => 'nullable|string|max:255',
            'cust_mailing_postal' => 'nullable|string|max:255',
            'cust_mailing_unit_no' => 'nullable|string|max:255',
            'cust_ap_name' => 'nullable|string|max:255',
            'cust_ap_address' => 'nullable|string|max:255',
            'cust_ap_city' => 'nullable|string|max:255',
            'cust_ap_state' => 'nullable|string|max:255',
            'cust_ap_country' => 'nullable|string|max:255',
            'cust_ap_postal' => 'nullable|string|max:255',
            'cust_ap_unit_no' => 'nullable|string|max:255',
            'cust_ap_email' => 'nullable|email|max:100',
            'cust_ap_phone' => 'nullable|string|max:255',
            'cust_ap_phone_ext' => 'nullable|string|max:255',
            'cust_ap_fax' => 'nullable|string|max:255',
            'cust_broker_name' => 'nullable|string|max:255',
            'cust_bkp_notes' => 'nullable|string|max:255',
            'cust_bkspl_notes' => 'nullable|string|max:255',
            'cust_credit_status' => 'nullable|string|max:255',
            'cust_credit_mop' => 'nullable|string|max:255',
            'cust_credit_appd' => 'nullable|string|max:255',
            'cust_credit_expd' => 'nullable|string|max:255',
            'cust_credit_terms' => 'nullable|string|max:255',
            'cust_credit_limit' => 'nullable|string|max:255',
            'cust_credit_notes' => 'nullable|string|max:255',
            'cust_credit_application' => 'nullable|boolean',
            'cust_credit_currency' => 'nullable|string|max:255',
            'cust_sbk_agreement' => 'nullable',
            'cust_credit_agreement' => 'nullable',
            'cust_contact' => 'nullable|array',
            'cust_contact.*.name' => 'nullable|string|max:255',
            'cust_contact.*.phone' => 'nullable|string|max:255',
            'cust_contact.*.ext' => 'nullable|string|max:255',
            'cust_contact.*.email' => 'nullable|email|max:100',
            'cust_contact.*.fax' => 'nullable|string|max:255',
            'cust_contact.*.designation' => 'nullable|string|max:255',
            'cust_equipment' => 'nullable|array',
            'cust_equipment.*.equipment' => 'nullable|string|max:255',
        ]);

        // Sanitize all fields
        foreach ($validatedData as $key => &$value) {
            if (is_string($value)) {
                $value = trim($value); // Trim strings
            } elseif (is_array($value)) {
                foreach ($value as &$item) {
                    if (is_array($item)) {
                        foreach ($item as $subKey => &$subValue) {
                            if (is_string($subValue)) {
                                $subValue = trim($subValue); // Trim nested strings
                            }
                        }
                    } elseif (is_string($item)) {
                        $item = trim($item); // Trim strings in arrays
                    }
                }
            } elseif (is_numeric($value)) {
                $value = (float) $value; // Ensure numeric fields are cast properly
            }
        }

        // Handle file uploads (if any)
        if ($request->hasFile('cust_sbk_agreement')) {
            $validatedData['cust_sbk_agreement'] = $request->file('cust_sbk_agreement')->store('agreements');
        }

        if ($request->hasFile('cust_credit_agreement')) {
            $validatedData['cust_credit_agreement'] = $request->file('cust_credit_agreement')->store('agreements');
        }

        // Handle JSON encoding for arrays
        if (isset($validatedData['cust_contact']) && is_array($validatedData['cust_contact'])) {
            $validatedData['cust_contact'] = json_encode($validatedData['cust_contact']);
        }

        if (isset($validatedData['cust_equipment']) && is_array($validatedData['cust_equipment'])) {
            $validatedData['cust_equipment'] = json_encode($validatedData['cust_equipment']);
        }

        // Update the customer data
        $customer->update($validatedData);

        return response()->json($customer);
    }

    /**
     * Delete a specific customer.
     */
    public function destroy(string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
