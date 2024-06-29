<?php
namespace App\Constraints;

use App\Models\User;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;

/**
 * Check if JWT token belongs to a user or not
 * Class SubjectMustBeAValidUser
 */
final class SubjectMustBeAValidUser implements Constraint
{
    /**
     * @param Token $token
     * @return void
     */
    public function assert(Token $token): void
    {
        if (!$token instanceof UnencryptedToken) {
            throw new ConstraintViolation('You should pass a plain token');
        }

        if (!$this->existsInDatabase($token->claims()->get('sub'))) {
            throw new ConstraintViolation('Token related to an unknown user');
        }
    }

    /**
     * @param string $userId
     * @return bool
     */
    private function existsInDatabase(string $userId): bool
    {
        return !empty(User::find($userId));
    }
}
