<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    public function index(Request $request)
    {
        $user = $request->user(); // Authenticated user from Sanctum

        if ($user->role === 'admin' || $user->role === 'carrier') {
            // Admins & carriers can see all customers
            $customers = $this->customer->orderBy('created_at', 'desc')->get();
            return response()->json($customers);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

    public function store(Request $request)
    {
        try {
            $validator = $this->validateCustomer($request);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $customerData = $validator->validated();

            // Decode JSON fields
            $customerData['cust_contact'] = is_string($customerData['cust_contact']) ? json_decode($customerData['cust_contact'], true) : $customerData['cust_contact'];
            $customerData['cust_equipment'] = is_string($customerData['cust_equipment']) ? json_decode($customerData['cust_equipment'], true) : $customerData['cust_equipment'];

            // Handle file uploads
            if ($request->hasFile('cust_sbk_agreement')) {
                $customerData['cust_sbk_agreement'] = $request->file('cust_sbk_agreement')->store('customer_agreements', 'public');
            }

            if ($request->hasFile('cust_credit_agreement')) {
                $customerData['cust_credit_agreement'] = $request->file('cust_credit_agreement')->store('customer_agreements', 'public');
            }

            // Create the customer
            $customer = Customer::create($customerData);

            Log::info('Customer Created:', $customer->toArray());

            return response()->json($customer, 201);
        } catch (\Exception $e) {
            Log::error('Error storing customer:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
        if ($validator->fails()) {
            dd($validator->errors()->all());
        }
    }


    public function show(string $id)
    {
        return $this->customer->find($id);
    }

    public function update(Request $request, string $id)
    {
        try {
            $customer = $this->customer->find($id);

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $validatedData = $this->validateCustomer($request, $customer->id);

            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }
            $customerData = $validatedData->validated();

            // Decode JSON fields
            $customerData['cust_contact'] = is_string($customerData['cust_contact']) ? json_decode($customerData['cust_contact'], true) : $customerData['cust_contact'];
            $customerData['cust_equipment'] = is_string($customerData['cust_equipment']) ? json_decode($customerData['cust_equipment'], true) : $customerData['cust_equipment'];


            // Handle file uploads
            if ($request->hasFile('cust_sbk_agreement')) {
                $customerData['cust_sbk_agreement'] = $request->file('cust_sbk_agreement')->store('customer_agreements', 'public');
            }

            if ($request->hasFile('cust_credit_agreement')) {
                $customerData['cust_credit_agreement'] = $request->file('cust_credit_agreement')->store('customer_agreements', 'public');
            }

            // Update the customer record
            $customer->update($validatedData->validated());

            return response()->json($customer);
        } catch (\Exception $e) {
            Log::error('Error updating customer:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy(string $id)
    {
        $customer = $this->customer->find($id);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }

    private function validateCustomer(Request $request)
    {
        return Validator::make($request->all(), [

            //Customer Info
            'cust_type' => 'nullable|string|in:Manufacturer,Trader,Distributor,Retailer,Freight Forwarder',
            'cust_name' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'cust_ref_no' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'cust_website' => 'nullable|max:150|url',
            'cust_email' => 'nullable|max:255|email',
            'cust_contact_no' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'cust_contact_no_ext' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9\-]*$/',
            'cust_tax_id' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9._\- ]*$/',

            //Primary Address
            'cust_primary_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_primary_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_primary_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_primary_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_primary_postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s\-]*$/',
            'cust_primary_unit_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9#\-\s]*$/',

            //Mailing Address
            'cust_mailing_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_mailing_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_mailing_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_mailing_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_mailing_postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s\-]*$/',
            'cust_mailing_unit_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9#\-\s]*$/',

            //Account Payable
            'cust_ap_name' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_ap_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_ap_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_ap_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_ap_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_ap_postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s\-]*$/',
            'cust_ap_unit_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9#\-\s]*$/',
            'cust_ap_email' => 'nullable|max:255|email',
            'cust_ap_phone' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'cust_ap_phone_ext' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'cust_ap_fax' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',

            //Custom Broker
            'cust_broker_name' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_bkp_notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_bkspl_notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            //Credit
            'cust_credit_status' => 'nullable|string|in:Approved,Not Approved',
            'cust_credit_mop' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'cust_credit_appd' => 'nullable|date',
            'cust_credit_expd' => 'nullable|date|after_or_equal:cust_credit_appd',
            'cust_credit_terms' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_credit_limit' => 'nullable|integer|min:0|max:9999999999',
            'cust_credit_notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'cust_credit_application' => 'nullable|boolean',
            'cust_credit_currency' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9\-]*$/',
            'cust_sbk_agreement' => 'nullable|string',
            'cust_credit_agreement' => 'nullable|string',

            //Contacts
            'cust_contact' => 'nullable|array',
            'cust_contact.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'cust_contact.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'cust_contact.*.ext' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'cust_contact.*.email' => 'nullable|max:255|email',
            'cust_contact.*.fax' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'cust_contact.*.designation' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.,\'\-]*$/',

            //Equipments
            'cust_equipment' => 'nullable|array',
            'cust_equipment.*.equipment' => 'nullable|string|in:Van,Reefer,Flatbed,Triaxle,Maxi,Btrain,Roll tite',
        ]);
    }
}
