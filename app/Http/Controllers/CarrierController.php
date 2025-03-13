<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\Request;

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
        $carrierData = $request->all();

        if ($request->hasFile('coi_cert')) {
            $carrierData['coi_cert'] = $request->file('coi_cert')->store('documents/coi_cert');
        }
        if ($request->hasFile('brok_carr_aggmt')) {
            $carrierData['brok_carr_aggmt'] = $request->file('brok_carr_aggmt')->store('documents/brok_carr_aggmt');
        }
        if (isset($carrierData['contact']) && is_array($carrierData['contact'])) {
            $carrierData['contact'] = json_encode($carrierData['contact']);
        }
        if (isset($carrierData['equipment']) && is_array($carrierData['equipment'])) {
            $carrierData['equipment'] = json_encode($carrierData['equipment']);
        }
        if (isset($carrierData['lane']) && is_array($carrierData['lane'])) {
            $carrierData['lane'] = json_encode($carrierData['lane']);
        }

        return $this->carrier->create($carrierData);
    }

    public function show(Carrier $carrier)
    {
        return response()->json($carrier);
    }


    public function update(Request $request, string $id)
    {
        $carrier = $this->carrier->find($id);

        $carrierData = $request->all();

        // Handle file uploads if they exist
        if ($request->hasFile('coi_cert')) {
            $carrierData['coi_cert'] = $request->file('coi_cert')->store('documents/coi_cert');
        }
        if ($request->hasFile('brok_carr_aggmt')) {
            $carrierData['brok_carr_aggmt'] = $request->file('brok_carr_aggmt')->store('documents/brok_carr_aggmt');
        }
        if (isset($carrierData['contact']) && is_array($carrierData['contact'])) {
            $carrierData['contact'] = json_encode($carrierData['contact']);
        }
        if (isset($carrierData['equipment']) && is_array($carrierData['equipment'])) {
            $carrierData['equipment'] = json_encode($carrierData['equipment']);
        }
        if (isset($carrierData['lane']) && is_array($carrierData['lane'])) {
            $carrierData['lane'] = json_encode($carrierData['lane']);
        }
        // Update the carrier with the validated and processed data
        $carrier->update($carrierData);

        return response()->json($carrier);
    }

    public function destroy(Carrier $carrier)
    {
        $carrier->delete();

        return response()->json(['message' => 'Carrier deleted successfully.']);
    }
}
