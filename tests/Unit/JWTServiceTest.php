<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Services\JWTService
 */
class JWTServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        // any test specific settings will go here
        parent::setUp();

    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testCreateToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);

        $this->assertNotEmpty($token);
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testParseToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);
        $parsedToken = $jwtService->parseToken($token);

        $this->assertEquals($user->id, $parsedToken->claims()->get('uid'));
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testValidateToken()
    {
        $user = User::factory()->create();

        $jwtService = new JWTService();
        $token = $jwtService->createToken($user);
        $parsedToken = $jwtService->parseToken($token);
        $this->assertTrue($jwtService->validateToken($parsedToken));
    }
}
