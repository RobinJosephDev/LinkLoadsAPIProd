<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    /**
     * Get all customers.
     */
    public function index()
    {
        return response()->json($this->customer->all());
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $customerData = $request->all();

        if (is_array($customerData)) {
            $processedCustomers = array_map(function ($customer) {

                if (isset($customer['cust_contact']) && is_array($customer['cust_contact'])) {
                    $order['cust_contact'] = json_encode($customer['cust_contact']);
                }
                if (isset($customer['cust_equipment']) && is_array($customer['cust_equipment'])) {
                    $customer['cust_equipment'] = json_encode($customer['cust_equipment']);
                }

                return $customer;
            }, $customerData);

            // Insert all processed orders at once
            Customer::insert($processedCustomers);

            return response()->json(['message' => 'Customers inserted successfully'], 201);
        }

        return response()->json(['error' => 'Invalid data format.'], 400);
    }
    /**
     * Display a specific customer.
     */
    public function show(string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);  // Return 404 if customer not found
        }

        return response()->json($customer);
    }

    /**
     * Update a specific customer.
     */
    public function update(Request $request, string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Update the customer data
        $customer->update($request->all());

        return response()->json($customer);
    }

    /**
     * Delete a specific customer.
     */
    public function destroy(string $id)
    {
        $customer = $this->customer->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
