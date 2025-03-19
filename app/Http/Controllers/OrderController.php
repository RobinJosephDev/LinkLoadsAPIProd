<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    protected $order;

    public function __construct()
    {
        $this->order = new Order();
    }


    public function index()
    {
        return response()->json($this->order->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateOrder($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $orderData = $validatedData->validated();
        $order = $this->order->create($orderData);

        return response()->json($order, 201);
    }

    public function show(string $id)
    {
        return $this->order->find($id);
    }

    public function update(Request $request, string $id)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $validatedData = $this->validateOrder($request, $order->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $order->update($validatedData->validated());

        return response()->json($order);
    }

    public function destroy(string $id)
    {
        $order = $this->order->find($id);
        return $order->delete();
    }

    private function validateOrder(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            // General
            'customer' => 'required|string|min:1',
            'customer_ref_no' => 'required|string|min:1',
            'branch' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'booked_by' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'account_rep' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'sales_rep' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'customer_po_no' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\-_\/]*$/',

            // Shipment
            'commodity' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'equipment' => ['nullable', 'string', Rule::in(["Dry Van 53'", "Flat Bed 53'", "Reefer 53'"])],
            'load_type' => ['nullable', 'string', Rule::in(['Partial', 'FTL', 'LTL'])],
            'temperature' => 'nullable|numeric|min:-100|max:100',

            // Origin Location
            'origin_location' => 'nullable|array',
            'origin_location.*.address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'origin_location.*.city' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'origin_location.*.state' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'origin_location.*.postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'origin_location.*.country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]*$/',
            'origin_location.*.date' => 'nullable|date_format:Y-m-d',
            'origin_location.*.time' => 'nullable|date_format:H:i',
            'origin_location.*.currency' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'origin_location.*.equipment' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'origin_location.*.pickup_po' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-]*$/',
            'origin_location.*.phone' => 'nullable|string|max:30|regex:/^\+?[0-9\-()\s]*$/',
            'origin_location.*.packages' => 'nullable|integer|min:1|max:99999',
            'origin_location.*.weight' => 'nullable|numeric|min:0|max:1000000',
            'origin_location.*.dimensions' => 'nullable|string|max:100|regex:/^\d+x\d+x\d+$/',
            'origin_location.*.notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',

            // Destination Location
            'destination_location' => 'nullable|array',
            'destination_location.*.address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'destination_location.*.city' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'destination_location.*.state' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'destination_location.*.postal' => 'nullable|string|max:20|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'destination_location.*.country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]*$/',
            'destination_location.*.date' => 'nullable|date_format:Y-m-d', 
            'destination_location.*.time' => 'nullable|date_format:H:i',
            'destination_location.*.currency' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'destination_location.*.equipment' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'destination_location.*.pickup_po' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-]*$/',
            'destination_location.*.phone' => 'nullable|string|max:30|regex:/^\+?[0-9\-()\s]*$/',
            'destination_location.*.packages' => 'nullable|integer|min:1|max:99999',
            'destination_location.*.weight' => 'nullable|numeric|min:0|max:1000000',
            'destination_location.*.dimensions' => 'nullable|string|max:100|regex:/^\d+x\d+x\d+$/',
            'destination_location.*.notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',

            // Specs
            'hot' => 'boolean',
            'team' => 'boolean',
            'air_ride' => 'boolean',
            'tarp' => 'boolean',
            'hazmat' => 'boolean',

            // Revenue
            'currency' => 'nullable|string|in:CAD,USD',
            'base_price' => 'nullable|numeric|min:0|max:1000000',

            // Charges
            'charges' => 'nullable|array',
            'charges.*.type' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'charges.*.charge' => 'nullable|numeric|min:0|max:1000000',
            'charges.*.percent' => 'nullable|string|in:Flat,Percentage',

            // Discounts
            'discounts' => 'nullable|array',
            'discounts.*.type' => 'nullable|string|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'discounts.*.charge' => 'nullable|numeric|min:0|max:1000000',
            'discounts.*.percent' => 'nullable|string|in:Flat,Percentage',

            // Tax
            'gst' => 'nullable|numeric|min:0|max:1000000',
            'pst' => 'nullable|numeric|min:0|max:1000000',
            'hst' => 'nullable|numeric|min:0|max:1000000',
            'qst' => 'nullable|numeric|min:0|max:1000000',
            'final_price' => 'nullable|numeric|min:0|max:1000000',
            'notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
        ]);
    }
}
