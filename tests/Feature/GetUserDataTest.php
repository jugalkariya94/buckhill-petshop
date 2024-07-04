<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Tests\TestCase;

class GetUserDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected JWTService $jwtService;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'id' => 1,
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->jwtService = new JWTService(new \App\Models\JWTToken());
        $this->token = $this->jwtService->createToken($this->user);
    }

    /**
     * Test getting user data successfully.
     *
     * @return void
     */
    public function testGetUserDataSuccessfully()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/user');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => $this->user->email,
                'uuid' => $this->user->uuid,
            ]);
    }

    /**
     * Test getting user data without authentication.
     *
     * @return void
     */
    public function testGetUserDataWithoutAuthentication()
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }
}
