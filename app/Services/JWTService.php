<?php

namespace App\Services;

use App\Constraints\SubjectMustBeAValidUser;
use App\Models\JWTToken;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use DateTimeImmutable;
use Lcobucci\JWT\Token;

/**
 * Class JWTService
 *
 * Service for handling JWT token creation, parsing and validation.
 */
class JWTService
{
    /**
     * @var Configuration
     */
    private Configuration $config;

    private JWTToken $model;

    /**
     * JWTService constructor.
     *
     * Initializes the JWT configuration.
     */
    public function __construct(JWTToken $model)
    {
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(config('auth.jwt.private_key'), config('auth.jwt.passphrase')),
            InMemory::file(config('auth.jwt.public_key'), config('auth.jwt.passphrase'))
        );
        $this->model = $model;
    }

    /**
     * Create a JWT token for a user.
     *
     * @param Authenticatable $user
     * @return string
     */
    public function createToken(Authenticatable $user): string
    {
        $now = new DateTimeImmutable();

        $tokenData = [
            // Because we are using hardcoded ID property instead of UUID or getAuthIdentifier(), we need to ignore the type check
            'user_id' => $user->id, // @phpstan-ignore-line
            'unique_id' => bin2hex(random_bytes(16)),
            'token_title' => 'auth_token',
            'expires_at' => $now->modify('+'.config('auth.jwt.ttl') .'minutes')
        ];

        $this->model->create($tokenData);

        return $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy($tokenData['unique_id'])
            ->relatedTo($user->getAuthIdentifier())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($tokenData['expires_at'])
            ->withClaim('uid', $user->getAuthIdentifier())
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    /**
     * Parse a JWT token.
     *
     * @param non-empty-string $token
     * @return Token
     */
    public function parseToken(string $token): Token
    {
        return $this->config->parser()->parse($token);
    }

    /**
     * Validate a JWT token.
     *
     * @param Token $token
     * @return bool
     */
    public function validateToken(Token $token): bool
    {
        // check if token is expired
        if ($token->isExpired(now())) {
            return false;
        }
        $this->config->setValidationConstraints(new SubjectMustBeAValidUser());
        $constraints = $this->config->validationConstraints();
        return $this->config->validator()->validate($token, ...$constraints);
    }

    /**
     * Get user uuid from token.
     *
     * @param non-empty-string $token
     * @return string|null
     */
    public function getUserUuidFromToken(string $token): string|null
    {
        $parsedToken = $this->parseToken($token);
        if ($this->validateToken($parsedToken)) {
            // Code analysis fails because of extended interface but is intended
            // ref: https://github.com/lcobucci/jwt/issues/611
            //@phpstan-ignore-next-line
            return $parsedToken->claims()->get('sub');
        }
        return null;
    }

    /**
     * Check if token was logged out.
     *
     * @param non-empty-string $token
     * @return bool
     */
    public function isTokenLoggedOut(string $token): bool
    {
        $tokenUniqueId = $this->getTokenUniqueId($token);
        return $this->model->where('unique_id', $tokenUniqueId)->expired()->exists();
    }

    /**
     * Get unique id from token.
     *
     * @param non-empty-string $token
     * @return string|null
     */
    public function getTokenUniqueId(string $token): string|null
    {
        $parsedToken = $this->parseToken($token);
        $uniqueId = null;
        if ($this->validateToken($parsedToken)) {
            // Code analysis fails because of extended interface but is intended
            // ref: https://github.com/lcobucci/jwt/issues/611
            //@phpstan-ignore-next-line
            $uniqueId =  $parsedToken->claims()->get('jti', null);
        }

        return $uniqueId;
    }

    /**
     * Get user from token unique id.
     *
     * @param string $tokenUniqueId
     * @return User|null
     */
    public function getUserFromTokenUniquId(string $tokenUniqueId): User|null
    {
        $token = $this->model->where('unique_id', $tokenUniqueId)->firstOrFail();
        return $token->user;
    }

    /**
     * Update last used field of token.
     *
     * @param string $tokenUniqueId
     * @return void
     */
    public function tokenUsed(string $tokenUniqueId): void
    {
        $this->model->where('unique_id', $tokenUniqueId)->update(['last_used_at' => now()]);
    }

    /**
     * Mark token as expired.
     *
     * @param non-empty-string $token
     * @return void
     */
    public function markTokenAsExpired(string $token): void
    {
        $tokenUniqueId = $this->getTokenUniqueId($token);
        $this->model->where('unique_id', $tokenUniqueId)->update(['last_used_at' => now(), 'expires_at' => now()]);
    }
}
