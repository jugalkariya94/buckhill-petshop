<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Tests\TestCase;

class GetUserDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Configuration $jwtConfig;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->jwtConfig = Configuration::forAsymmetricSigner(
            new \Lcobucci\JWT\Signer\Rsa\Sha256(),
            InMemory::file(config('auth.jwt.private_key'), config('auth.jwt.passphrase')),
            InMemory::file(config('auth.jwt.public_key'), config('auth.jwt.passphrase'))
        );

        $this->token = $this->jwtConfig->builder()
            ->issuedBy(env('APP_URL'))
            ->permittedFor(env('APP_URL'))
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->relatedTo($this->user->getAuthIdentifier())
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable())
            ->expiresAt((new \DateTimeImmutable())->modify('+1 hour'))
            ->withClaim('uid', $this->user->getAuthIdentifier())
            ->getToken($this->jwtConfig->signer(), $this->jwtConfig->signingKey())
            ->toString();
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
