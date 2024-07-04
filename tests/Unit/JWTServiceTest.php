<?php

namespace Tests\Unit;

use App\Models\JWTToken;
use App\Models\User;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JWTServiceTest extends TestCase
{
    use RefreshDatabase;

    private JWTService $jwtService;
    protected User $user;

    protected function setUp(): void
    {
        // any test specific settings will go here
        parent::setUp();
        $this->jwtService = new JWTService(new JWTToken());
        $this->user = User::factory()->create([
            'id' => 1,
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

    }

    public function testCreateToken()
    {
        $token = $this->jwtService->createToken($this->user);

        $this->assertNotEmpty($token);
    }

    public function testParseToken()
    {
        $token = $this->jwtService->createToken($this->user);
        $parsedToken = $this->jwtService->parseToken($token);

        $this->assertEquals($this->user->getAuthIdentifier(), $parsedToken->claims()->get('uid'));
    }

    public function testValidateToken()
    {
        $token = $this->jwtService->createToken($this->user);
        $parsedToken = $this->jwtService->parseToken($token);
        $this->assertTrue($this->jwtService->validateToken($parsedToken));
    }
}
