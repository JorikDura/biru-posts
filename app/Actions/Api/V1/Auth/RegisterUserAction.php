<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\RegistrationRequest;
use App\Models\User;

final readonly class RegisterUserAction
{
    public function __construct(
        private RegistrationRequest $request
    ) {
    }

    /**
     * @return User
     */
    public function __invoke(): User
    {
        return User::create($this->request->validated());
    }
}
