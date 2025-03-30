<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_customer_routes()
    {
        $response = $this->getJson('/api/customer');
        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_fetch_all_customers()
    {
        $this->authenticate();
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customer');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        $this->authenticate();
        $data = [
            'cust_name' => 'Test Customer',
            'cust_email' => 'test@example.com',
            'cust_contact_no' => '1234567890',
        ];

        $response = $this->postJson('/api/customer', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Customer created successfully']);

        $this->assertDatabaseHas('customers', ['cust_name' => 'Test Customer']);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_customer()
    {
        $this->authenticate();
        $response = $this->postJson('/api/customer', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cust_name']);
    }

    /** @test */
    public function it_can_show_a_customer()
    {
        $this->authenticate();
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customer/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $customer->id]);
    }

    /** @test */
    public function it_returns_404_if_customer_not_found()
    {
        $this->authenticate();
        $response = $this->getJson('/api/customer/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $this->authenticate();
        $customer = Customer::factory()->create();

        $updatedData = ['cust_name' => 'Updated Customer Name'];

        $response = $this->putJson("/api/customer/{$customer->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'cust_name' => 'Updated Customer Name']);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        $this->authenticate();
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customer/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Customer deleted successfully']);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
