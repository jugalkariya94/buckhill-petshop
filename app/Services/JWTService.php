<?php

namespace App\Services;

use App\Constraints\SubjectMustBeAValidUser;
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

    /**
     * JWTService constructor.
     *
     * Initializes the JWT configuration.
     */
    public function __construct()
    {
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(config('auth.jwt.private_key'), config('auth.jwt.passphrase')),
            InMemory::file(config('auth.jwt.public_key'), config('auth.jwt.passphrase'))
        );
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

        return $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->relatedTo($user->getAuthIdentifier())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
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
        $this->config->setValidationConstraints(new SubjectMustBeAValidUser());
        $constraints = $this->config->validationConstraints();
        return $this->config->validator()->validate($token, ...$constraints);
    }

    /**
     * Get user uuid from token.
     *
     * @param string $token
     * @return string
     */
    public function getUserUuidFromToken(string $token): string
    {
        $parsedToken = $this->parseToken($token);
        $this->validateToken($parsedToken);
        return $parsedToken->claims()->get('sub');
    }
}
