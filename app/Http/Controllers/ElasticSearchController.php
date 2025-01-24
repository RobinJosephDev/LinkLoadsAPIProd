<?php

namespace App\Http\Controllers;
class ElasticSearchController extends Controller
{
    public function createIndex()
    {
        $client = \Elastic\Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();

        $params = [
            'index' => 'orders',
            'body'  => [
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'keyword'],
                        'customer' => ['type' => 'text'],
                        'final_price' => ['type' => 'keyword'],
                        'created_at' => ['type' => 'date'],
                    ]
                ]
            ]
        ];

        // Create the index in Elasticsearch
        $response = $client->indices()->create($params);

        return response()->json($response);
    }
}
