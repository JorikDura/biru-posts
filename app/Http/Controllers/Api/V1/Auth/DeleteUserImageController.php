<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Images\DeleteImageAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserImageController extends Controller
{
    /**
     * @param  User  $user
     * @param  DeleteImageAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        #[CurrentUser] User $user,
        DeleteImageAction $action
    ): Response {
        $action($user);

        return response()->noContent();
    }
}
