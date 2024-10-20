<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Model\PurgeModelAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Response;
use ReflectionException;

class DeleteUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Comment  $comment
     * @param  PurgeModelAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        User $user,
        Comment $comment,
        PurgeModelAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
