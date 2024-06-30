<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginuserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user to be used in tests
        $this->user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Test logging in successfully.
     *
     * @return void
     */
    public function testLoginSuccessfully()
    {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
            ]);
    }

    /**
     * Test validation errors when logging in.
     *
     * @return void
     */
    public function testLoginValidationErrors()
    {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test incorrect credentials.
     *
     * @return void
     */
    public function testLoginIncorrectCredentials()
    {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials',
            ]);
    }
}
