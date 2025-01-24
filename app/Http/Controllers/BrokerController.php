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
        // Fetch all brokers
        return $this->broker->all();
    }

    public function store(Request $request)
    {
        $brokerData = $request->all();

        return $this->broker->create($brokerData);
    }

    public function show(string $id)
    {
        return $this->broker->find($id);
    }

    public function update(Request $request, string $id)
    {
        $broker = $this->broker->find($id);

        if (!$broker) {
            return response()->json(['error' => 'Broker not found'], 404);
        }

        $brokerData = $request->all();


        $broker->update($brokerData);

        return response()->json($broker);
    }

    public function destroy(string $id)
    {
        $broker = $this->broker->find($id);
        return $broker->delete();
    }
}
