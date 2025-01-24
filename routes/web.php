<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-elasticsearch', function () {
    $client = \Elastic\Elasticsearch\ClientBuilder::create()->setHosts(['localhost:9200'])->build();

    // Check if the index exists
    $indexName = 'test-elasticsearch';
    $params = ['index' => $indexName];

    try {
        // Check if the index exists
        $response = $client->indices()->exists($params);

        if (!$response) {
            // Create the index if it doesn't exist
            $client->indices()->create($params);
            $response = ['message' => 'Index created: ' . $indexName];
        }

        dd($response);
    } catch (\Exception $e) {
        // Handle error
        dd(['error' => $e->getMessage()]);
    }
});
