<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'is_admin' => false,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => fake()->dateTime(),
            'password' => Hash::make('password'), // password
            'avatar' => null,
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber(),
            'is_marketing' => fake()->boolean(),
            'last_login_at' => fake()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
            'email' => 'admin@buckhill.co.uk',
            'password' => Hash::make('password'), // password
        ]);
    }

}
