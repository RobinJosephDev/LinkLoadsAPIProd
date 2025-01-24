<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\User;

class AuthControllerTest extends TestCase
{
use RefreshDatabase;

    public function test_register_creates_user_and_returns_token()
    {
        $data = [
            'name' => 'Rickshaw',
            'username' => 'rick',
            'email' => 'rick@gmail.com',
            'password' => 'ricks123',
            'password_confirmation' => 'ricks123',
            'role' => 'carrier',
            'emp_code' => '1234e',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'token', 'user']);

        // Match the correct username
        $this->assertDatabaseHas('users', [
            'username' => 'rick',
            'email' => 'rick@gmail.com',
        ]);
    }



    public function test_register_fails_with_invalid_data()
    {
        $data = [
            'name' => '',
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role' => 'invalid-role',
            'emp_code' => '',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role', 'emp_code']);
    }
//
//
//    public function test_login_returns_token_for_valid_credentials()
//    {
//        $user = User::factory()->create([
//            'username' => 'testuser',
//            'password' => bcrypt('password123'),
//        ]);
//
//        $response = $this->postJson('/api/login', [
//            'username' => 'testuser',
//            'password' => 'password123',
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJsonStructure(['message', 'token', 'user']);
//    }
//
//
//    public function test_login_fails_with_invalid_credentials()
//    {
//        $user = User::factory()->create([
//            'username' => 'testuser',
//            'password' => bcrypt('password123'),
//        ]);
//
//        $response = $this->postJson('/api/login', [
//            'username' => 'testuser',
//            'password' => 'wrongpassword',
//        ]);
//
//        $response->assertStatus(422)
//            ->assertJson(['message' => 'The provided credentials are incorrect.']);
//
//    }
//
//    public function test_logout_revokes_tokens()
//    {
//        $user = User::factory()->create();
//        $token = $user->createToken('API Token')->plainTextToken;
//
//        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
//            ->postJson('/api/logout');
//
//        $response->assertStatus(200)
//            ->assertJson(['message' => 'Logged out successfully']);
//
//        $this->assertDatabaseMissing('personal_access_tokens', [
//            'tokenable_id' => $user->id,
//        ]);
//    }
//
//    public function test_logout_fails_without_authentication()
//    {
//        $response = $this->postJson('/api/logout');
//
//        $response->assertStatus(401)
//            ->assertJson(['message' => 'Unauthenticated.']);
//    }

}
