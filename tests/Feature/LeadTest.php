<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
    }

    public function testGetCachedData()
    {
        Sanctum::actingAs($this->user);

        Cache::shouldReceive('get')
            ->with('key', 'default value')
            ->andReturn('mocked value');

        $response = $this->getJson('/api/lead');

        $response->assertStatus(200);
    }

    public function test_get_all_leads()
    {
        Sanctum::actingAs($this->user);
        Lead::factory()->count(3)->create();

        $response = $this->getJson('/api/lead');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_create_lead()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'lead_no' => 'L12345',
            'lead_date' => now()->toDateString(),
            'customer_name' => 'John Doe',
            'phone' => '1234567890',
            'lead_type' => 'New',
            'lead_status' => 'Open'
        ];

        $response = $this->postJson('/api/lead', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'lead_no', 'lead_date', 'customer_name']);

        $this->assertDatabaseHas('leads', ['lead_no' => 'L12345']);
    }

    public function test_update_lead()
    {
        Sanctum::actingAs($this->user);

        $lead = Lead::factory()->create([
            'lead_no' => 'L54321',
            'lead_status' => 'Open'
        ]);

        $data = [
            'lead_no' => 'L12345',
            'lead_date' => now()->toDateString(),
            'customer_name' => 'John Doe',
            'lead_type' => 'Existing',
            'lead_status' => 'Closed'
        ];

        $response = $this->putJson("/api/lead/{$lead->id}", $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'lead_no' => 'L12345',
            'lead_status' => 'Closed'
        ]);
    }

    public function test_delete_lead()
    {
        Sanctum::actingAs($this->user);

        $lead = Lead::factory()->create();

        $response = $this->deleteJson("/api/lead/{$lead->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('leads', ['id' => $lead->id]);
    }
}
