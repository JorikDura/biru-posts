<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment\Like;

use App\Actions\Api\V1\Like\LikeAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class LikeUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Comment  $comment
     * @param  LikeAction  $action
     * @return Response
     * @throws Exception
     */
    public function __invoke(
        User $user,
        Comment $comment,
        LikeAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
