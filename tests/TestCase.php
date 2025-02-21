<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.env' => 'testing']);
        config(['database.default' => env('DB_CONNECTION', 'pgsql_testing')]);
        config(['database.connections.pgsql.database' => env('DB_DATABASE', 'linkloads_testing')]);
    }
}
