<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;

class DeletePostCommentController extends Controller
{
    /**
     * @param  Post  $post
     * @param  Comment  $comment
     * @return Response
     */
    public function __invoke(
        Post $post,
        Comment $comment
    ): Response {
        $comment->delete();

        return response()->noContent();
    }
}
