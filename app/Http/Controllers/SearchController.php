<?php

// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    protected $elasticsearch;

    public function __construct()
    {
        $this->elasticsearch = \Elastic\Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();
    }

    /**
     * Search for an order by its ID in Elasticsearch.
     */
    public function searchById($orderId)
    {
        $params = [
            'index' => 'orders',
            'id'    => $orderId,
        ];

        try {
            $response = $this->elasticsearch->get($params);
            return response()->json($response['_source']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    /**
     * Search orders by a field (e.g., customer name).
     */
    /**
     * Search orders by a field (e.g., customer name).
     */
    public function searchByField(Request $request)
    {
        // Validate request input
        $validated = $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
        ]);

        $field = $validated['field'];
        $value = $validated['value'];

        // Set query type based on field type (use match for text fields and term for keyword fields)
        $query = [];
        if ($field == 'customer') {
            $query = ['match' => [$field => $value]];
        } else {
            $query = ['term' => [$field => $value]];
        }

        $params = [
            'index' => 'orders',
            'body'  => [
                'query' => $query
            ]
        ];

        try {
            // Execute the search query
            $response = $this->elasticsearch->search($params);

            // Log the response (convert to array before logging)
            Log::debug('Elasticsearch Response:', $response['hits']);

            // Check for hits
            if (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {
                return response()->json($response['hits']['hits']);
            } else {
                return response()->json(['message' => 'No results found'], 404);
            }
        } catch (\Elastic\Elasticsearch\Exception\ClientResponseException $e) {
            return response()->json(['error' => 'Elasticsearch query error: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }




}
