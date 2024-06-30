<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new user successfully.
     *
     * @return void
     */
    public function testCreateUserSuccessfully()
    {
        $response = $this->postJson('/api/v1/user/create', [
            'first_name' => 'John',
            'last_name' => 'doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => '1234 Elm St',
            'phone_number' => '1234567890',
            'is_marketing' => false,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Test validation errors when creating a user.
     *
     * @return void
     */
    public function testCreateUserValidationErrors()
    {
        $response = $this->postJson('/api/v1/user/create', [
            'first_name' => '',
            'last_name' => '',
            'email' => 'johndoeexample.com',
            'password' => 'password',
            'password_confirmation' => 'passwordqweqwe',
            'address' => '1234 Elm St',
            'phone_number' => '1234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'password'
            ]);
    }

    /**
     * Test email already taken error.
     *
     * @return void
     */
    public function testCreateUserEmailAlreadyTaken()
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->postJson('/api/v1/user/create', [
            'first_name' => 'John',
            'last_name' => 'doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => '1234 Elm St',
            'phone_number' => '1234567890',
            'is_marketing' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
