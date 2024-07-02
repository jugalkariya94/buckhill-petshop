<?php

namespace App\Services\Guards;

use App\Services\JWTService;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;

class JWTGuard implements Guard
{
    protected Request $request;
    protected JWTService $service;

    use GuardHelpers;
    public function __construct(UserProvider $provider, Request $request, JWTService $service)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->service = $service;
    }

    public function user()
    {
        // If the user is already set, return it
        if ($this->user) {
            return $this->user;
        }

        // Get the token from the request
        $tokenString = $this->request->bearerToken();

        // If no token is found, return null
        if (empty($tokenString)) {
            return null;
        }
        try {
            $loggedOutToken = $this->service->isTokenLoggedOut($tokenString);

            if ($loggedOutToken) {
                return null;
            }
            // Parse the token
            $authId = $this->service->getUserUuidFromToken($tokenString);

            $this->user = $this->provider->retrieveById($authId);

            return $this->user;
        } catch (\Exception $e) {
            return null;
        }
    }


    public function validate(array $credentials = [])
    {
        // JWT Guard does not validate using credentials
        return false;
    }


}

