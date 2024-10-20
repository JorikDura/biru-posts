<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Response;

class DeleteUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Comment  $comment
     * @return Response
     */
    public function __invoke(
        User $user,
        Comment $comment
    ): Response {
        $comment->delete();

        return response()->noContent();
    }
}
