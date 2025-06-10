<?php

namespace App\Http\Controllers;

use App\Models\Dispatch;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    public function index()
    {
        return Dispatch::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'carrier' => 'required|string',
            'contact' => 'required|string',
            'equipment' => 'required|string',
            'driver_mobile' => 'required|string',
            'truck_unit_no' => 'required|string',
            'trailer_unit_no' => 'required|string',
            'paps_pars_no' => 'required|string',
            'tracking_code' => 'required|string',
            'border' => 'required|string',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
            'charges' => 'nullable|array',
            'discounts' => 'nullable|array',
            'gst' => 'nullable|numeric',
            'pst' => 'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst' => 'nullable|numeric',
            'final_price' => 'required|numeric',
        ]);

        return Dispatch::create($validated);
    }

    public function show($id)
    {
        return Dispatch::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $dispatch = Dispatch::findOrFail($id);

        $validated = $request->validate([
            'carrier' => 'sometimes|required|string',
            'contact' => 'sometimes|required|string',
            'equipment' => 'sometimes|required|string',
            'driver_mobile' => 'sometimes|required|string',
            'truck_unit_no' => 'sometimes|required|string',
            'trailer_unit_no' => 'sometimes|required|string',
            'paps_pars_no' => 'sometimes|required|string',
            'tracking_code' => 'sometimes|required|string',
            'border' => 'sometimes|required|string',
            'currency' => 'sometimes|required|string',
            'rate' => 'sometimes|required|numeric',
            'charges' => 'nullable|array',
            'discounts' => 'nullable|array',
            'gst' => 'nullable|numeric',
            'pst' => 'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst' => 'nullable|numeric',
            'final_price' => 'sometimes|required|numeric',
        ]);

        $dispatch->update($validated);

        return $dispatch;
    }

    public function destroy($id)
    {
        $dispatch = Dispatch::findOrFail($id);
        $dispatch->delete();

        return response()->json(['message' => 'Dispatch deleted']);
    }
}
