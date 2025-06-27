<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    protected $company;

    public function __construct()
    {
        $this->company = new Company();
    }

    public function index()
    {
        return response()->json($this->company->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {
            // Validate request and get validator instance
            $validator = $this->validateCompany($request);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Extract validated data as an array
            $companyData = $validator->validated();

            // Decode JSON fields
            $companyData['bank_info'] = is_string($companyData['bank_info']) ? json_decode($companyData['bank_info'], true) : $companyData['bank_info'];
            $companyData['cargo_insurance'] = is_string($companyData['cargo_insurance']) ? json_decode($companyData['cargo_insurance'], true) : $companyData['cargo_insurance'];
            $companyData['liablility_insurance'] = is_string($companyData['liablility_insurance']) ? json_decode($companyData['liablility_insurance'], true) : $companyData['liablility_insurance'];

            // Handle file uploads
            if ($request->hasFile('company_package')) {
                $companyData['company_package'] = $request->file('company_package')->store('company_documents', 'public');
            }

            if ($request->hasFile('insurance')) {
                $companyData['insurance'] = $request->file('insurance')->store('company_documents', 'public');
            }

            // Create the carrier
            $company = Company::create($companyData);

            Log::info('Company Created:', $company->toArray());

            return response()->json($company, 201);
        } catch (\Exception $e) {
            Log::error('Error storing company:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        return $this->company->find($id);
    }

    public function update(Request $request, string $id)
    {
        try {
            $company = $this->company->find($id);

            if (!$company) {
                return response()->json(['error' => 'Company not found'], 404);
            }

            $validatedData = $this->validateCompany($request, $company->id);

            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }

            // Extract validated data as an array
            $companyData = $validatedData->validated();

            // Decode JSON fields
            $companyData['bank_info'] = is_string($companyData['bank_info']) ? json_decode($companyData['bank_info'], true) : $companyData['bank_info'];
            $companyData['cargo_insurance'] = is_string($companyData['cargo_insurance']) ? json_decode($companyData['cargo_insurance'], true) : $companyData['cargo_insurance'];
            $companyData['liablility_insurance'] = is_string($companyData['liablility_insurance']) ? json_decode($companyData['liablility_insurance'], true) : $companyData['liablility_insurance'];


            // Handle file uploads
            if ($request->hasFile('company_package')) {
                $companyData['company_package'] = $request->file('company_package')->store('company_documents', 'public');
            }

            if ($request->hasFile('insurance')) {
                $companyData['insurance'] = $request->file('insurance')->store('company_documents', 'public');
            }

            $company->update($validatedData->validated());

            return response()->json($company);
        } catch (\Exception $e) {
            Log::error('Error updating company:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy(string $id)
    {
        $company = $this->company->find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $company->delete();
        return response()->json(['message' => 'Company deleted successfully'], 200);
    }

    /**
     * Validate vendor input data.
     */
    /**
     * @var array $validator
     */
    private function validateCompany(Request $request)
    {
        return Validator::make($request->all(), [

            //General
            'name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]+$/',
            'invoice_terms' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'rate_conf_terms' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'quote_terms' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'invoice_reminder' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            //Address
            'address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s,.\'\-]*$/',
            'state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s,.\'\-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s,.\'\-]*$/',
            'postal' =>  'nullable|string|max:20|regex:/^[a-zA-Z0-9-\s]*$/',
            'email' => 'nullable|max:255|email',
            'phone' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'cell' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'fax' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'invoice_prefix' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-\s_]*$/',
            'SCAC' => 'nullable|string|max:10|regex:/^[A-Z0-9-]*$/',
            'docket_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'carrier_code' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'gst_hst_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9-]*$/',
            'qst_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9-]*$/',
            'ca_bond_no' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9-]*$/',
            'website' => 'nullable|max:150|url',
            'obsolete' => 'nullable|boolean',

            //Other Info
            'us_tax_id' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'payroll_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'wcb_no' => 'nullable|string|max:50|regex:/^\d*$/',
            'dispatch_email' => 'nullable|max:255|email',
            'ap_email' => 'nullable|max:255|email',
            'ar_email' => 'nullable|max:255|email',
            'cust_comm_email' => 'nullable|max:255|email',
            'quot_email' => 'nullable|max:255|email',

            // Banking Info
            'bank_info' => 'nullable|array',
            'bank_info.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'bank_info.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s\+]{0,30}$/',
            'bank_info.*.email' => 'nullable|max:255|email',
            'bank_info.*.address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'bank_info.*.us_account_no' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'bank_info.*.cdn_account_no' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            // Cargo Insurance
            'cargo_insurance' => 'nullable|array',
            'cargo_insurance.*.company' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'cargo_insurance.*.policy_start' => 'nullable|date',
            'cargo_insurance.*.policy_end' => 'nullable|date',
            'cargo_insurance.*.amount' => 'nullable|numeric|min:0|max:1000000',

            // Liability Insurance
            'liablility_insurance' => 'nullable|array',
            'liablility_insurance.*.company' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'liablility_insurance.*.policy_start' => 'nullable|date',
            'liablility_insurance.*.policy_end' => 'nullable|date',
            'liablility_insurance.*.amount' => 'nullable|numeric|min:0|max:1000000',

            //Documents Upload
            'company_package' => 'nullable|string',
            'insurance' => 'nullable|string',

        ]);
    }
}
