<?php

namespace Tests\Feature;

use App\Models\LeadFollowup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeadFollowupTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_get_all_lead_followups()
    {
        Sanctum::actingAs($this->user);
        LeadFollowup::factory()->count(3)->create();

        $response = $this->getJson('/api/lead-followup');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_create_lead_followup()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'lead_no' => 'L12345',
            'lead_date' => now()->toDateString(),
            'customer_name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'lead_status' => 'Open',
            'next_follow_up_date' => now()->addDays(7)->toDateString(), 
            'contacts' => [
                ['name' => 'Jane Doe', 'phone' => '9876543210', 'email' => 'jane@example.com']
            ]
        ];

        $response = $this->postJson('/api/lead-followup', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'lead_no', 'lead_date', 'customer_name']);
    }


    public function test_update_lead_followup()
    {
        Sanctum::actingAs($this->user);

        $leadFollowup = LeadFollowup::factory()->create(['lead_status' => 'Open']);
        $data = [
            'lead_no' => 'FR5335',
            'lead_status' => 'Closed'
        ];

        $response = $this->putJson("/api/lead-followup/{$leadFollowup->id}", $data);

        $response->assertStatus(200);
        //$this->assertDatabaseHas('lead-followup', ['id' => $leadFollowup->id, 'lead_status' => 'Closed']);
    }

    public function test_delete_lead_followup()
    {
        Sanctum::actingAs($this->user);

        $leadFollowup = LeadFollowup::factory()->create();

        $response = $this->deleteJson("/api/lead-followup/{$leadFollowup->id}");

        $response->assertStatus(204);
       // $this->assertDatabaseMissing('lead-followup', ['id' => $leadFollowup->id]);
    }
}
