<?php

namespace App\Services\Guards;

use App\Services\JWTService;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;

/**
 *
 */
class JWTGuard implements Guard
{
    /**
     * @var Request
     */
    protected Request $request;
    /**
     * @var JWTService
     */
    protected JWTService $service;

    /**
     * @var UserProvider|null
     */
    protected $provider;

    use GuardHelpers;

    /**
     * @param UserProvider|null $provider
     * @param Request $request
     * @param JWTService $service
     */
    public function __construct(Request $request, JWTService $service, ?UserProvider $provider = null)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->service = $service;
    }

    /**
     * @return \App\Models\User|Authenticatable|null
     */
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
//            $authId = $this->service->getUserUuidFromToken($tokenString);
//            if (!$authId) {
//                return null;
//            }
//
//            $this->user = $this->provider->retrieveById($authId);
            $tokenUniqueId = $this->service->getTokenUniqueId($tokenString);
            if (!$tokenUniqueId) {
                return null;
            }

            // get user from token unique id
            $this->user = $this->service->getUserFromTokenUniquId($tokenUniqueId);

            $this->service->tokenUsed($tokenUniqueId);

            return $this->user;
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param array<mixed> $credentials
     * @return false
     */
    public function validate(array $credentials = [])
    {
        // JWT Guard does not validate using credentials
        return false;
    }


}

