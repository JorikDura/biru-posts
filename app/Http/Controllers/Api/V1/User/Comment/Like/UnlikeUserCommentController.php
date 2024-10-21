<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment\Like;

use App\Actions\Api\V1\Like\UnlikeAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class UnlikeUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Comment  $comment
     * @param  UnlikeAction  $action
     * @return Response
     * @throws Exception
     */
    public function __invoke(
        User $user,
        Comment $comment,
        UnlikeAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
