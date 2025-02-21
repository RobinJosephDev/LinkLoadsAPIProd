<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     * @test
     */
    public function check_if_array_has_key_value(): void
    {
        $userResponse = ["name" => 'Robin', "value" => 1];
        $this->assertEquals(1, $userResponse["value"]);
        $this->assertArrayHasKey('name', $userResponse);
    }


    /**
     * test_format_currency_helper
     *
     * @return void
     */
    public function test_format_currency_helper()
    {
        $this->assertEquals('₹1,000.00', formatCurrency(1000));
    }



}
