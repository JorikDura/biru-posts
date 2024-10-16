<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\LogoutAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    /**
     * @param  LogoutAction  $action
     * @param  User  $user
     * @return Response
     */
    public function __invoke(
        LogoutAction $action,
        #[CurrentUser] User $user
    ): Response {
        $action($user);

        return response()->noContent();
    }
}
