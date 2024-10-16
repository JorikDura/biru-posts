<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\CreateTokenAction;
use App\Actions\Api\V1\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TokenResource;

class RegistrationController extends Controller
{
    /**
     * @param  RegisterUserAction  $registerUser
     * @param  CreateTokenAction  $createTokenAction
     * @return TokenResource
     */
    public function __invoke(
        RegisterUserAction $registerUser,
        CreateTokenAction $createTokenAction,
    ): TokenResource {
        $user = $registerUser();

        $token = $createTokenAction($user);

        return TokenResource::make($token);
    }
}
