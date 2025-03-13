<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    protected $vendor;

    public function __construct()
    {
        $this->vendor = new Vendor();
    }

    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        return response()->json($this->vendor->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateVendor($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $vendorData = $validatedData->validated();
        $vendor = $this->vendor->create($vendorData);

        return response()->json($vendor, 201);
    }

    /**
     * Display the specified vendor.
     */
    public function show(string $id)
    {
        return $this->vendor->find($id);
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, string $id)
    {
        $vendor = $this->vendor->find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $validatedData = $this->validateVendor($request, $vendor->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $vendor->update($validatedData->validated());

        return response()->json($vendor);
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(string $id)
    {
        $vendor = $this->vendor->find($id);
        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $vendor->delete();
        return response()->json(['message' => 'Vendor deleted successfully'], 200);
    }

    /**
     * Validate vendor input data.
     */
    private function validateVendor(Request $request, $id = null)
    {
        return Validator::make($request->all(), [

            //Vendor Type
            'type' => 'required|string|in:Vendor,Factoring Company',

            //Vendor Details
            'legal_name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'remit_name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'vendor_type' => 'nullable|string|max:50|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'service' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'scac' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'docket_number' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9-]*$/',
            'vendor_code' => 'nullable|string|max:20|regex:/^[a-zA-Z\s0-9-]*$/',
            'gst_hst_number' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'qst_number' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'ca_bond_number' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9-]*$/',
            'website' => 'nullable|max:255|url',

            //Primary Address
            'primary_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'primary_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_country' =>  'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_postal' => 'nullable|regex:/^[a-zA-Z0-9\s]{0,20}$/',
            'primary_email' => 'nullable|max:255|email',
            'primary_phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'primary_fax' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',

            //Mailing Address
            'sameAsPrimary' => 'nullable|boolean',
            'mailing_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'mailing_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_postal' => 'nullable|regex:/^[a-zA-Z0-9\s]{0,20}$/',
            'mailing_email' => 'nullable|max:255|email',
            'mailing_phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'mailing_fax' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',

            //Additional
            'us_tax_id' => 'nullable|string|size:9',
            'payroll_no' => 'nullable|string|max:50|regex:/^\d*$/',
            'wcb_no' => 'nullable|string|max:50|regex:/^\d*$/',

            //Acc Receivable
            'ar_name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'ar_email' => 'nullable|max:255|email',
            'ar_contact_no' =>  'nullable|string|max:15|regex:/^\d*$/',
            'ar_ext' => 'nullable|string|max:10|regex:/^\d*$/',

            //Acc Payable
            'ap_name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'ap_email' => 'nullable|max:255|email',
            'ap_contact_no' => 'nullable|string|max:15|regex:/^\d*$/',
            'ap_ext' => 'nullable|string|max:10|regex:/^\d*$/',

            //Banking
            'bank_name' => 'nullable|string|max:150|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'bank_phone' => 'nullable|string|max:15|regex:/^\d*$/',
            'bank_email' => 'nullable|max:255|email',
            'bank_us_acc_no' => 'nullable|string|size:9',
            'bank_cdn_acc_no' => 'nullable|string|max:12|regex:/^\d*$/',
            'bank_address' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',

            // Cargo Insurance
            'cargo_company' => 'nullable|string|max:150|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'cargo_policy_start' => 'nullable|date',
            'cargo_policy_end' => 'nullable|date|after_or_equal:cargo_policy_start',
            'cargo_ins_amt' => 'nullable|numeric|min:0',

            // Liability Insurance
            'liab_company' => 'nullable|string|max:150|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'liab_policy_start' => 'nullable|date',
            'liab_policy_end' => 'nullable|date|after_or_equal:liab_policy_start',
            'liab_ins_amt' => 'nullable|numeric|min:0',

            // Contacts
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'contacts.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.email' => 'nullable|max:255|email',
            'contacts.*.fax' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.designation' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.,\'\-]*$/',
        ]);
    }
}
