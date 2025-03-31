<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuoteController extends Controller
{

    protected $quote;

    public function __construct()
    {
        $this->quote = new Quote();
    }

    // Show all quotes
    public function index()
    {
        return response()->json($this->quote->orderBy('created_at', 'desc')->get());
    }

    // Store a new quote
    public function store(Request $request)
    {
        $validatedData = $this->validateQuote($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $quoteData = $validatedData->validated();
        $quote = $this->quote->create($quoteData);

        return response()->json($quote, 201);
    }

    // Show a specific quote by ID
    public function show(string $id)
    {
        return $this->quote->find($id);
    }

    // Update a specific quote
    public function update(Request $request, string $id)
    {
        $quote = $this->quote->find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);
        }

        $validatedData = $this->validateQuote($request, $quote->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $quote->update($validatedData->validated());

        return response()->json($quote);
    }

    // Delete a specific quote
    public function destroy(string $id)
    {
        $quote = $this->quote->find($id);
        return $quote->delete();
    }

    private function validateQuote(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            // General
            'quote_type' => ['required', 'string', Rule::in(["FTL", "LTL"])],
            'quote_customer' => 'required|string|min:1|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]+$/',
            'quote_cust_ref_no' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]+$/',
            'quote_booked_by' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_temperature' => 'nullable|numeric|min:-100|max:100',
            'quote_hot' => 'boolean',
            'quote_team' => 'boolean',
            'quote_air_ride' => 'boolean',
            'quote_tarp' => 'boolean',
            'quote_hazmat' => 'boolean',

            // Pickup
            'quote_pickup' => 'nullable|array',
            'quote_pickup.*.address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_pickup.*.city' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_pickup.*.state' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_pickup.*.postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_pickup.*.country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]*$/',
            'quote_pickup.*.date' => 'nullable|date',
            'quote_pickup.*.time' => 'nullable|date_format:H:i',
            'quote_pickup.*.currency' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'quote_pickup.*.equipment' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_pickup.*.pickup_po' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-]*$/',
            'quote_pickup.*.phone' => 'nullable|string|max:30|regex:/^\+?[0-9\-()\s]*$/',
            'quote_pickup.*.packages' => 'nullable|integer|min:1|max:99999',
            'quote_pickup.*.weight' => 'nullable|numeric|min:0|max:1000000',
            'quote_pickup.*.dimensions' => 'nullable|string|max:100|regex:/^\d+x\d+x\d+$/',
            'quote_pickup.*.notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',

            // Delivery
            'quote_delivery' => 'nullable|array',
            'quote_delivery.*.address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_delivery.*.city' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_delivery.*.state' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_delivery.*.postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_delivery.*.country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]*$/',
            'quote_delivery.*.date' => 'nullable|date',
            'quote_delivery.*.time' => 'nullable|date_format:H:i',
            'quote_delivery.*.currency' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'quote_delivery.*.equipment' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'quote_delivery.*.pickup_po' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-]*$/',
            'quote_delivery.*.phone' => 'nullable|string|max:30|regex:/^\+?[0-9\-()\s]*$/',
            'quote_delivery.*.packages' => 'nullable|integer|min:1|max:99999',
            'quote_delivery.*.weight' => 'nullable|numeric|min:0|max:1000000',
            'quote_delivery.*.dimensions' => 'nullable|string|max:100|regex:/^\d+x\d+x\d+$/',
            'quote_delivery.*.notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
        ]);
    }
}
