<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment\Like;

use App\Actions\Api\V1\Like\UnlikeAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\Response;

class UnlikePostCommentController extends Controller
{
    /**
     * @param  Post  $post
     * @param  Comment  $comment
     * @param  UnlikeAction  $action
     * @return Response
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        Comment $comment,
        UnlikeAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
