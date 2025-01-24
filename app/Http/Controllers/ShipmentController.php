<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{

    protected $shipment;

    public function __construct()
    {
        $this->shipment = new Shipment();
    }

    // Get all shipments
    public function index()
    {
        $shipments = Shipment::all();
        return response()->json($shipments);
    }

    // Create a new shipment
    public function store(Request $request)
    {
        $shipmentData = $request->all();

        return $this->shipment->create($shipmentData);
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

        // If the lead is not found, return a 404 response with an error message
        if (!$shipment) {
            return response()->json(['error' => 'Shipment not found'], 404);
        }

        // Get the data to update from the request
        $shipmentData = $request->all();

        // Perform the update
        $shipment->update($shipmentData);

        // Return the updated lead
        return response()->json($shipment);
    }

    // Delete a shipment by ID
    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();
        return response()->json(null, 204);
    }
}
