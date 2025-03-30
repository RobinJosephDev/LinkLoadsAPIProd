<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_fetch_all_quotes()
    {
        Quote::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/quote');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_quote()
    {
        $data = [
            'quote_pickup' => ['city' => 'New York', 'zip' => '10001'],
            'quote_delivery' => ['city' => 'Los Angeles', 'zip' => '90001'],
            'quote_type' => 'FTL'
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/quote', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('quotes', ['quote_type' => 'FTL']);
    }


    /** @test */
    public function it_can_show_a_quote()
    {
        $quote = Quote::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/quote/{$quote->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $quote->id]);
    }

    /** @test */
    public function it_returns_404_if_quote_not_found()
    {
        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/quote/9999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Quote not found']);
    }

    /** @test */
    public function it_can_update_a_quote()
    {
        $quote = Quote::factory()->create();

        $updatedData = ['quote_type' => 'LTL'];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/quote/{$quote->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['quote_type' => 'LTL']);

        $this->assertDatabaseHas('quotes', ['id' => $quote->id, 'quote_type' => 'LTL']);
    }


    /** @test */
    public function it_can_delete_a_quote()
    {
        $quote = Quote::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/quote/{$quote->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Quote deleted successfully']);

        $this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
    }
}
