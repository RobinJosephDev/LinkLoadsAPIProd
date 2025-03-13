<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;

    public function __construct()
    {
        $this->order = new Order();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->order->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store newly created resources in storage (bulk insert).
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'customer' => 'required|string|max:255',
            'customer_ref_no' => 'nullable|string|max:100',
            'branch' => 'nullable|string|max:100',
            'booked_by' => 'nullable|string|max:255',
            'account_rep' => 'nullable|string|max:255',
            'sales_rep' => 'nullable|string|max:255',
            'customer_po_no' => 'nullable|string|max:100',
            'commodity' => 'nullable|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'load_type' => 'nullable|string|max:100',
            'temperature' => 'nullable|numeric',

            'origin_location' => 'nullable|array',
            'origin_location.*.address' => 'nullable|string|max:255',
            'origin_location.*.city' => 'nullable|string|max:255',
            'origin_location.*.state' => 'nullable|string|max:255',
            'origin_location.*.postal' => 'nullable|string|max:255',
            'origin_location.*.country' => 'nullable|string|max:255',
            'origin_location.*.date' => 'nullable|date',
            'origin_location.*.time' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'origin_location.*.currency' => 'nullable|string|max:50',
            'origin_location.*.equipment' => 'nullable|string|max:255',
            'origin_location.*.pickup_po' => 'nullable|string|max:255',
            'origin_location.*.phone' => 'nullable|string|max:50',
            'origin_location.*.packages' => 'nullable|string|max:255',
            'origin_location.*.weight' => 'nullable|string|max:255',
            'origin_location.*.dimensions' => 'nullable|string|max:255',
            'origin_location.*.notes' => 'nullable|string|max:255',

            'destination_location' => 'nullable|array',
            'destination_location.*.address' => 'nullable|string|max:255',
            'destination_location.*.city' => 'nullable|string|max:255',
            'destination_location.*.state' => 'nullable|string|max:255',
            'destination_location.*.postal' => 'nullable|string|max:255',
            'destination_location.*.country' => 'nullable|string|max:255',
            'destination_location.*.date' => 'nullable|date',
            'destination_location.*.time' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'destination_location.*.currency' => 'nullable|string|max:50',
            'destination_location.*.equipment' => 'nullable|string|max:255',
            'destination_location.*.pickup_po' => 'nullable|string|max:255',
            'destination_location.*.phone' => 'nullable|string|max:50',
            'destination_location.*.packages' => 'nullable|string|max:255',
            'destination_location.*.weight' => 'nullable|string|max:255',
            'destination_location.*.dimensions' => 'nullable|string|max:255',
            'destination_location.*.notes' => 'nullable|string|max:255',

            'hot' => 'nullable|boolean',
            'team' => 'nullable|boolean',
            'air_ride' => 'nullable|boolean',
            'tarp' => 'nullable|boolean',
            'hazmat' => 'nullable|boolean',
            'currency' => 'nullable|string|max:10',
            'base_price' => 'nullable|numeric',

            'charges' => 'nullable|array',
            'charges.*.type' => 'nullable|string|max:255',
            'charges.*.charge' => 'nullable|numeric',
            'charges.*.percent' => 'nullable|string|max:255',

            'discounts' => 'nullable|array',
            'discounts.*.type' => 'nullable|string|max:255',
            'discounts.*.charge' => 'nullable|numeric',
            'discounts.*.percent' => 'nullable|string|max:255',

            'gst' => 'nullable|numeric',
            'pst' => 'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        // Sanitize all fields
        foreach ($validatedData as $key => &$value) {
            if (is_string($value)) {
                $value = trim($value); // Trim strings
            } elseif (is_array($value)) {
                foreach ($value as &$item) {
                    if (is_array($item)) {
                        foreach ($item as $subKey => &$subValue) {
                            if (is_string($subValue)) {
                                $subValue = trim($subValue); // Trim nested strings
                            }
                        }
                    } elseif (is_string($item)) {
                        $item = trim($item); // Trim strings in arrays
                    }
                }
            } elseif (is_numeric($value)) {
                $value = (float) $value; // Ensure numeric fields are cast properly
            }
        }

        // Encode arrays into JSON for storage
        if (isset($validatedData['origin_location']) && is_array($validatedData['origin_location'])) {
            $validatedData['origin_location'] = json_encode($validatedData['origin_location']);
        }

        if (isset($validatedData['destination_location']) && is_array($validatedData['destination_location'])) {
            $validatedData['destination_location'] = json_encode($validatedData['destination_location']);
        }

        if (isset($validatedData['charges']) && is_array($validatedData['charges'])) {
            $validatedData['charges'] = json_encode($validatedData['charges']);
        }

        if (isset($validatedData['discounts']) && is_array($validatedData['discounts'])) {
            $validatedData['discounts'] = json_encode($validatedData['discounts']);
        }

        // Save the order
        return $this->order->create($validatedData);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->order->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'customer' => 'required|string|max:255',
            'customer_ref_no' => 'nullable|string|max:100',
            'branch' => 'nullable|string|max:100',
            'booked_by' => 'nullable|string|max:255',
            'account_rep' => 'nullable|string|max:255',
            'sales_rep' => 'nullable|string|max:255',
            'customer_po_no' => 'nullable|string|max:100',
            'commodity' => 'nullable|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'load_type' => 'nullable|string|max:100',
            'temperature' => 'nullable|numeric',

            'origin_location' => 'nullable|array',
            'origin_location.*.address' => 'nullable|string|max:255',
            'origin_location.*.city' => 'nullable|string|max:255',
            'origin_location.*.state' => 'nullable|string|max:255',
            'origin_location.*.postal' => 'nullable|string|max:255',
            'origin_location.*.country' => 'nullable|string|max:255',
            'origin_location.*.date' => 'nullable|date',
            'origin_location.*.time' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'origin_location.*.currency' => 'nullable|string|max:50',
            'origin_location.*.equipment' => 'nullable|string|max:255',
            'origin_location.*.pickup_po' => 'nullable|string|max:255',
            'origin_location.*.phone' => 'nullable|string|max:50',
            'origin_location.*.packages' => 'nullable|string|max:255',
            'origin_location.*.weight' => 'nullable|string|max:255',
            'origin_location.*.dimensions' => 'nullable|string|max:255',
            'origin_location.*.notes' => 'nullable|string|max:255',

            'destination_location' => 'nullable|array',
            'destination_location.*.address' => 'nullable|string|max:255',
            'destination_location.*.city' => 'nullable|string|max:255',
            'destination_location.*.state' => 'nullable|string|max:255',
            'destination_location.*.postal' => 'nullable|string|max:255',
            'destination_location.*.country' => 'nullable|string|max:255',
            'destination_location.*.date' => 'nullable|date',
            'destination_location.*.time' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'destination_location.*.currency' => 'nullable|string|max:50',
            'destination_location.*.equipment' => 'nullable|string|max:255',
            'destination_location.*.pickup_po' => 'nullable|string|max:255',
            'destination_location.*.phone' => 'nullable|string|max:50',
            'destination_location.*.packages' => 'nullable|string|max:255',
            'destination_location.*.weight' => 'nullable|string|max:255',
            'destination_location.*.dimensions' => 'nullable|string|max:255',
            'destination_location.*.notes' => 'nullable|string|max:255',

            'hot' => 'nullable|boolean',
            'team' => 'nullable|string|max:255',
            'air_ride' => 'nullable|boolean',
            'tarp' => 'nullable|boolean',
            'hazmat' => 'nullable|boolean',
            'currency' => 'nullable|string|max:10',
            'base_price' => 'nullable|numeric',
            'charges' => 'nullable|array',
            'discounts' => 'nullable|array',
            'gst' => 'nullable|numeric',
            'pst' => 'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        foreach ($validatedData as $key => &$value) {
            if (is_string($value)) {
                $value = trim($value); // Trim strings
            } elseif (is_array($value)) {
                foreach ($value as &$item) {
                    if (is_array($item)) {
                        foreach ($item as $subKey => &$subValue) {
                            if (is_string($subValue)) {
                                $subValue = trim($subValue); // Trim nested strings
                            }
                        }
                    } elseif (is_string($item)) {
                        $item = trim($item); // Trim strings in arrays
                    }
                }
            } elseif (is_numeric($value)) {
                $value = (float) $value; // Ensure numeric fields are cast properly
            }
        }

        if (isset($validatedData['origin_location']) && is_array($validatedData['origin_location'])) {
            $validatedData['origin_location'] = json_encode($validatedData['origin_location']);
        }

        if (isset($validatedData['destination_location']) && is_array($validatedData['destination_location'])) {
            $validatedData['destination_location'] = json_encode($validatedData['destination_location']);
        }

        if (isset($validatedData['charges']) && is_array($validatedData['charges'])) {
            $validatedData['charges'] = json_encode($validatedData['charges']);
        }

        if (isset($validatedData['discounts']) && is_array($validatedData['discounts'])) {
            $validatedData['discounts'] = json_encode($validatedData['discounts']);
        }

        // Update the order
        $order->update($validatedData);

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = $this->order->find($id);
        return $order->delete();
    }
}
