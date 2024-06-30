<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JWTServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        // any test specific settings will go here
        parent::setUp();

    }

    public function testCreateToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);

        $this->assertNotEmpty($token);
    }

    public function testParseToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);
        $parsedToken = $jwtService->parseToken($token);

        $this->assertEquals($user->getAuthIdentifier(), $parsedToken->claims()->get('uid'));
    }

    public function testValidateToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);
        $parsedToken = $jwtService->parseToken($token);
        $this->assertTrue($jwtService->validateToken($parsedToken));
    }
}
