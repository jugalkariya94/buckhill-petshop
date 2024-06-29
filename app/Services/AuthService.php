<?php

namespace App\Services;

use App\Events\UserRegistered;
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
     *
     * @return User The registered user
     */
    public function register(array $data, bool $triggerEvent = true)
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


}
