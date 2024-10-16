<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class LogoutAction
{
    /**
     * @param  User  $user
     * @return void
     */
    public function __invoke(User $user): void
    {
        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();
    }
}
