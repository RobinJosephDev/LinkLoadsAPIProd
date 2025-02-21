<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        User::factory(10)->create();
    }
    /**
     * A basic feature test example.
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    /**
     * test_create_10_users_1
     *@test
     */
    public function test_create_10_users_1()
    {
        $response = DB::table("users")->get()->toArray();
        $this->assertArrayHasKey(9, $response);
        $this->assertCount(10, $response);
        $this->assertGreaterThan(5, count(($response)));
    }

    /**
     * test_create_10_users_2
     *
     * @return void
     */
    public function test_create_10_users_2()
    {
        User::factory(1)->create(["name" => "Roger"]);

        $response = DB::table("users")->where("name", "Roger")->first();
        // dd($response);
    }

    /**
     * test_create_10_users_3
     *
     * @return void
     */
    public function test_create_10_users_3()
    {
        User::factory(1)->create(["name" => "Roger"]);

        $response = DB::table("users")->get();
        $this->assertEquals(11, count($response));
        // dd(gettype($response));
    }

    /**
     * test_create_10_users_4
     *
     * @return void
     */
    public function test_create_10_users_4()
    {
        User::factory(1)->create(["name" => "Roger"]);

        $response = DB::table("users")->get();

        $this->assertEquals(11, count($response));
        $this->assertTrue($response->contains(function ($item, $key) {
            return $item->name == "Roger";
        }));
        $this->assertObjectHasProperty("name", $response[0]);
    }
}
