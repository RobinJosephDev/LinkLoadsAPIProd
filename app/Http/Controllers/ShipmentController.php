<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{

    protected $shipment;

    public function __construct()
    {
        $this->shipment = new Shipment();
    }


    public function index()
    {
        return response()->json($this->shipment->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateShipment($request);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $shipmentData = $validatedData->validated();
        $shipment = $this->shipment->create($shipmentData);

        return response()->json($shipment, 201);
    }

    // Get a single shipment by ID
    public function show(string $id)
    {
        return $this->shipment->find($id);
    }

    // Update a shipment by ID
    public function update(Request $request, string $id)
    {
        $shipment = $this->shipment->find($id);

        if (!$shipment) {
            return response()->json(['error' => 'Shipment not found'], 404);
        }

        $validatedData = $this->validateShipment($request, $shipment->id);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $shipment->update($validatedData->validated());

        return response()->json($shipment);
    }

    // Delete a shipment by ID
    public function destroy(string $id)
    {
        $shipment = $this->shipment->find($id);
        return $shipment->delete();
    }

    private function validateShipment(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'ship_load_date' => 'nullable|date',
            'ship_ftl_ltl' => ['nullable', 'string', Rule::in(["FTL", "LTL"])],
            'ship_pickup_location' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'ship_delivery_location' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'ship_driver' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'ship_weight' => 'nullable|numeric|min:0|max:999999',
            'ship_equipment' => 'nullable|string|max:150|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'ship_price' => 'nullable|numeric|min:0|max:999999',
            'ship_notes' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9\s.,\'\-]*$/',
            'ship_tarp' => 'boolean'
        ]);
    }
}
