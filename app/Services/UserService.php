<?php

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use App\Exceptions\InvalidCredentialsException;
use App\Models\UsedToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 *
 * Service for handling user related actions.
 */
class UserService
{
    private $model;

    public function __construct()
    {
        $this->model = app()->make(User::class);
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param array<mixed> $data
     * @return User
     */
    public function getFromUuid(string $uuid): User
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * @param array<mixed> $data
     * @return User
     */
    public function getFromEmail(string $email): User
    {
        return $this->model->where('email', $email)->first();
    }

}
