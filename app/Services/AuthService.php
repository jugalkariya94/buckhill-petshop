<?php

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use App\Exceptions\InvalidCredentialsException;
use App\Models\UsedToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 *
 * Service for handling Authentication operations.
 */
class AuthService
{

    /**
     * @param array<mixed> $data The data to register the user with
     * @param bool $triggerEvent Whether to trigger the UserRegistered event
     * @return User The registered user
     *
     * Register a new user
     */
    public function register(array $data, bool $triggerEvent = true): User
    {
        // Create a new user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'],
            'phone_number' => $data['phone_number'],
            'is_marketing' => !empty($data['is_marketing']),
            'is_admin' => false,
            'avatar' => $data['avatar'] ?? null,
        ]);

        // Fire the UserRegistered event
        if ($triggerEvent)
            event(new UserRegistered($user));

        // Return the user
        return $user;

    }

    /**
     * @param string $email The email of the user
     * @param string $password The password of the user
     * @param bool $triggerEvent Whether to trigger the UserRegistered event
     * @return User The logged-in user
     * @throws InvalidCredentialsException
     *
     * login a user
     */
    public function login(string $email, string $password, bool $triggerEvent = true): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        // Fire the UserRegistered event
        if ($triggerEvent)
            event(new UserLoggedIn($user));

        // Return the user
        return $user;

    }

    /**
     * @param string $token
     * @return void
     *
     * Logout a user
     */
    public function logout(string $token): void
    {
        // Add logggd out token to the database
        UsedToken::create(['token' => $token]);
    }

}
