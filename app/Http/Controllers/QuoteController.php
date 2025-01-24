<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{

    protected $quote;

    public function __construct()
    {
        $this->quote = new Quote();
    }
    // Show all quotes
    public function index()
    {
        $quotes = Quote::all();
        return response()->json($quotes);
    }

    // Store a new quote
    public function store(Request $request)
    {
        $quoteData = $request->all();

        if (isset($quoteData['quote_pickup']) && is_array($quoteData['quote_pickup'])) {
            $quoteData['quote_pickup'] = json_encode($quoteData['quote_pickup']);
        }

        if (isset($quoteData['quote_delivery']) && is_array($quoteData['quote_delivery'])) {
            $quoteData['quote_delivery'] = json_encode($quoteData['quote_delivery']);
        }

        return $this->quote->create($quoteData);
    }


    // Show a specific quote by ID
    public function show(string $id)
    {
        $quote = $this->quote->find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);  // Return 404 if customer not found
        }

        return response()->json($quote);
    }

    // Update a specific quote
    public function update(Request $request, string $id)
    {
        $quote = $this->quote->find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);
        }

        // Update the customer data
        $quote->update($request->all());

        return response()->json($quote);
    }

    // Delete a specific quote
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return response()->json(['message' => 'Quote deleted successfully']);
    }
}
