<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CarrierController extends Controller
{
    protected $carrier;

    public function __construct()
    {
        $this->carrier = new Carrier();
    }

    public function index()
    {
        return response()->json($this->carrier->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {
            // Validate request and get validator instance
            $validator = $this->validateCarrier($request);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Extract validated data as an array
            $carrierData = $validator->validated();

            // Decode JSON fields
            $carrierData['contacts'] = is_string($carrierData['contacts']) ? json_decode($carrierData['contacts'], true) : $carrierData['contacts'];
            $carrierData['equipments'] = is_string($carrierData['equipments']) ? json_decode($carrierData['equipments'], true) : $carrierData['equipments'];
            $carrierData['lanes'] = is_string($carrierData['lanes']) ? json_decode($carrierData['lanes'], true) : $carrierData['lanes'];

            // Handle file uploads
            if ($request->hasFile('brok_carr_aggmt')) {
                $carrierData['brok_carr_aggmt'] = $request->file('brok_carr_aggmt')->store('carrier_agreements', 'public');
            }

            if ($request->hasFile('coi_cert')) {
                $carrierData['coi_cert'] = $request->file('coi_cert')->store('coi_certificates', 'public');
            }

            // Create the carrier
            $carrier = Carrier::create($carrierData);

            Log::info('Carrier Created:', $carrier->toArray());

            return response()->json($carrier, 201);
        } catch (\Exception $e) {
            Log::error('Error storing carrier:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        return $this->carrier->find($id);
    }


    public function update(Request $request, string $id)
    {
        try {
            $carrier = $this->carrier->find($id);

            if (!$carrier) {
                return response()->json(['error' => 'Carrier not found'], 404);
            }

            $validatedData = $this->validateCarrier($request, $carrier->id);

            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }

            // Extract validated data as an array
            $carrierData = $validatedData->validated();

            // Decode JSON fields
            $carrierData['contacts'] = is_string($carrierData['contacts']) ? json_decode($carrierData['contacts'], true) : $carrierData['contacts'];
            $carrierData['equipments'] = is_string($carrierData['equipments']) ? json_decode($carrierData['equipments'], true) : $carrierData['equipments'];
            $carrierData['lanes'] = is_string($carrierData['lanes']) ? json_decode($carrierData['lanes'], true) : $carrierData['lanes'];

            // Handle file uploads
            if ($request->hasFile('brok_carr_aggmt')) {
                $carrierData['brok_carr_aggmt'] = $request->file('brok_carr_aggmt')->store('carrier_agreements', 'public');
            }

            if ($request->hasFile('coi_cert')) {
                $carrierData['coi_cert'] = $request->file('coi_cert')->store('coi_certificates', 'public');
            }

            $carrier->update($validatedData->validated());

            return response()->json($carrier);
        } catch (\Exception $e) {
            Log::error('Error updating carrier:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $carrier = $this->carrier->find($id);
        if (!$carrier) {
            return response()->json(['error' => 'Carrier not found'], 404);
        }

        $carrier->delete();
        return response()->json(['message' => 'Carrier deleted successfully'], 200);
    }

    /**
     * Validate vendor input data.
     */
    /**
     * @var array $validator
     */
    private function validateCarrier(Request $request)
    {
        return Validator::make($request->all(), [

            //General
            'dba' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'legal_name' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'remit_name' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'acc_no' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9-]*$/',
            'branch' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'website' => 'nullable|max:150|url',
            'fed_id_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'pref_curr' => 'nullable|string|in:USD,CAD',
            'pay_terms' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'form_1099' => 'nullable|boolean',
            'advertise' => 'nullable|boolean',
            'advertise_email' => 'nullable|max:255|email',
            'brok_carr_aggmt' => 'nullable|string',

            //Carrier Details
            'carr_type' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'rating' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'docket_no' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9-]*$/',
            'dot_number' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'wcb_no' => 'nullable|string|max:50|regex:/^\d*$/',
            'ca_bond_no' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9-]*$/',
            'us_bond_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-]*$/',
            'scac' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'smsc_code' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]*$/',
            'csa_approved' => 'nullable|boolean',
            'hazmat' => 'nullable|boolean',
            'approved' => 'nullable|boolean',

            //Liability Insurance
            'li_provider' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'-]*$/',
            'li_policy_no' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s.-]*$/',
            'li_coverage' => 'nullable|numeric',
            'li_start_date' => 'nullable|date',
            'li_end_date' => 'nullable|date',

            //Cargo Insurance    
            'ci_provider' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'-]*$/',
            'ci_policy_no' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s.-]*$/',
            'ci_coverage' => 'nullable|numeric',
            'ci_start_date' => 'nullable|date',
            'ci_end_date' => 'nullable|date|after_or_equal:ci_start_date',
            'coi_cert' => 'nullable|string',

            //Primary Address
            'primary_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'primary_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'primary_postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-\s]*$/',
            'primary_phone' => 'nullable|string|max:30|regex:/^[0-9-+()\s]*$/',

            //Mailing Address
            'mailing_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'mailing_city' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_state' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'mailing_postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9-\s]*$/',
            'mailing_phone' => 'nullable|string|max:30|regex:/^[0-9-+()\s]*$/',

            //Internal Notes
            'int_notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',

            // Contacts
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:200|regex:/^[a-zA-Z\s.,\'\-]*$/',
            'contacts.*.phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.email' => 'nullable|max:255|email',
            'contacts.*.fax' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'contacts.*.designation' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.,\'\-]*$/',

            // Equipments
            'equipments' => 'nullable|array',
            'equipments.*.equipment' => ['nullable', 'string', Rule::in(["Dry Van 53'", "Reefer 53'", "Flat Bed 53'"])],

            // Lanes
            'lanes' => 'nullable|array',
            'lanes.*.from' => ['nullable', 'string', Rule::in([
                'AB',
                'AK',
                'AL',
                'AR',
                'AZ',
                'BC',
                'CA',
                'CO',
                'CT',
                'DE',
                'FL',
                'GA',
                'HI',
                'IA',
                'ID',
                'IL',
                'IN',
                'KS',
                'KY',
                'LA',
                'MA',
                'MB',
                'MD',
                'ME',
                'MI',
                'MN',
                'MO',
                'MS',
                'MT',
                'NB',
                'NC',
                'ND',
                'NE',
                'NH',
                'NJ',
                'NL',
                'NM',
                'NS',
                'NV',
                'NY',
                'OH',
                'OK',
                'ON',
                'OR',
                'PA',
                'PE',
                'QC',
                'RI',
                'SC',
                'SD',
                'SK',
                'TN',
                'TX',
                'UT',
                'VA',
                'VT',
                'WA',
                'WI',
                'WV',
                'WY'
            ])],
            'lanes.*.to' => ['nullable', 'string', Rule::in([
                'AB',
                'AK',
                'AL',
                'AR',
                'AZ',
                'BC',
                'CA',
                'CO',
                'CT',
                'DE',
                'FL',
                'GA',
                'HI',
                'IA',
                'ID',
                'IL',
                'IN',
                'KS',
                'KY',
                'LA',
                'MA',
                'MB',
                'MD',
                'ME',
                'MI',
                'MN',
                'MO',
                'MS',
                'MT',
                'NB',
                'NC',
                'ND',
                'NE',
                'NH',
                'NJ',
                'NL',
                'NM',
                'NS',
                'NV',
                'NY',
                'OH',
                'OK',
                'ON',
                'OR',
                'PA',
                'PE',
                'QC',
                'RI',
                'SC',
                'SD',
                'SK',
                'TN',
                'TX',
                'UT',
                'VA',
                'VT',
                'WA',
                'WI',
                'WV',
                'WY'
            ])],
        ]);
    }
}
