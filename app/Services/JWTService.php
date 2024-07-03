<?php

namespace App\Services;

use App\Constraints\SubjectMustBeAValidUser;
use App\Models\JWTToken;
use App\Models\UsedToken;
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
            'user_id' => $user->id,
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
     * @param string $token
     * @return string|null
     */
    public function getUserUuidFromToken(string $token): string|null
    {
        $parsedToken = $this->parseToken($token);
        if ($this->validateToken($parsedToken)) {
            return $parsedToken->claims()->get('sub');
        }
        return null;
    }

    /**
     * Check if token was logged out.
     *
     * @param string $token
     * @return bool
     */
    public function isTokenLoggedOut(string $token): bool
    {

        return UsedToken::where('token', $token)->exists();
    }
}
