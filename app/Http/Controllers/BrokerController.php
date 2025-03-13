<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    protected $broker;

    public function __construct()
    {
        $this->broker = new Broker();
    }

    public function index()
    {
        return response()->json($this->broker->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        $broker = $this->broker->create($validatedData);
        return response()->json($broker, 201);
    }

    public function show(string $id)
    {
        $broker = $this->broker->find($id);
        if (!$broker) {
            return response()->json(['error' => 'Broker not found'], 404);
        }
        return response()->json($broker);
    }

    public function update(Request $request, string $id)
    {
        $broker = $this->broker->find($id);
        if (!$broker) {
            return response()->json(['error' => 'Broker not found'], 404);
        }

        $validatedData = $this->validateRequest($request, $broker->id);

        $broker->update($validatedData);
        return response()->json($broker);
    }

    public function destroy(string $id)
    {
        $broker = $this->broker->find($id);
        if (!$broker) {
            return response()->json(['error' => 'Broker not found'], 404);
        }

        $broker->delete();
        return response()->json(['message' => 'Broker deleted successfully'], 200);
    }

    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'broker_name' => 'required|string|max:200|regex:/^[a-zA-Z\s.\'\-]+$/',
            'broker_address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s,.\'\-]*$/',
            'broker_city'    => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'broker_state'   => 'nullable|string|max:200|regex:/^[a-zA-Z\s.\'\-]*$/',
            'broker_country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s.\'\-]*$/',
            'broker_postal'  => 'nullable|regex:/^[a-zA-Z0-9]{0,20}$/',
            'broker_email'   => 'nullable|max:255|email',
            'broker_phone' => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/',
            'broker_ext' => 'nullable|regex:/^\+?[0-9]{0,10}$/',
            'broker_fax'     => 'nullable|regex:/^[0-9\-\(\)\s]{0,30}$/'
        ];

        return $request->validate($rules);
    }
}
