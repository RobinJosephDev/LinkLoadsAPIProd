<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create(); // Create a test user
        Sanctum::actingAs($user); // Authenticate as this user
        return $user;
    }

    /** @test */
    public function it_can_list_orders()
    {
        $this->authenticate(); // Authenticate user before request

        Order::factory()->count(3)->create();

        $response = $this->getJson('/api/order');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        $this->authenticate(); // Authenticate user before request

        $data = [
            'customer' => 'Test Customer',
            'temperature' => 5,
            'final_price' => 1000,
            'origin_location' => [
                ['address' => '123 Test St', 'city' => 'Test City', 'state' => 'Test State']
            ],
            'destination_location' => [
                ['address' => '456 Destination St', 'city' => 'Dest City', 'state' => 'Dest State']
            ]
        ];

        $response = $this->postJson('/api/order', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['customer' => 'Test Customer']);
    }

    /** @test */
    public function it_can_show_an_order()
    {
        $this->authenticate(); // Authenticate user before request

        $order = Order::factory()->create();

        $response = $this->getJson("/api/order/{$order->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }

    /** @test */
    public function it_can_update_an_order()
    {
        $this->authenticate(); // Authenticate user before request

        $order = Order::factory()->create();  
        $updateData = ['customer' => 'Updated Customer'];

        $response = $this->putJson("/api/order/{$order->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['customer' => 'Updated Customer']);
    }

    /** @test */
    public function it_can_delete_an_order()
    {
        $this->authenticate(); // Authenticate user before request

        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/order/{$order->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
