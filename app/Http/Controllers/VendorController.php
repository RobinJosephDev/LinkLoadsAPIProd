<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{

    protected $vendor;

    public function __construct()
    {
        $this->vendor = new Vendor();
    }

    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        return $this->vendor->all();
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {

        $vendorData = $request->all();

        return $this->vendor->create($vendorData);
    }

    /**
     * Display the specified vendor.
     */
    public function show(string $id)
    {
        return $this->vendor->find($id);
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, string $id)
    {
        $vendor = $this->vendor->find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $vendorData = $request->all();


        $vendor->update($vendorData);

        return response()->json($vendor);
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(string $id)
    {
        $vendor = $this->vendor->find($id);
        return $vendor->delete();
    }
}
