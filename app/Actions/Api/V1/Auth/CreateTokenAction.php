<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth;

use App\Models\User;

final readonly class CreateTokenAction
{
    private const string TOKEN_NAME = 'access_token';

    /**
     * Generate a token for user.
     * @param  User  $user
     * @return string
     */
    public function __invoke(User $user): string
    {
        return $user->createToken(name: self::TOKEN_NAME)
            ->plainTextToken;
    }
}
