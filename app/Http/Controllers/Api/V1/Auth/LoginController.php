<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\CreateTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @param  LoginRequest  $request
     * @param  CreateTokenAction  $action
     * @return TokenResource|JsonResponse
     */
    public function __invoke(
        LoginRequest $request,
        CreateTokenAction $action
    ): TokenResource|JsonResponse {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(
                data: [
                    'message' => 'These credentials do not match our records.'
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = Auth::user();

        $token = $action($user);

        return TokenResource::make($token);
    }
}
