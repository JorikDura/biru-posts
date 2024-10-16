<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Model\PurgeModelAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use ReflectionException;

class DeleteUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  PurgeModelAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        User $user,
        PurgeModelAction $action
    ): Response {
        $action($user);

        return response()->noContent();
    }
}
