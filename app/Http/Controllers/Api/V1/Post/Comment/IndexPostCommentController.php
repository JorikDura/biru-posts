<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Actions\Api\V1\Post\Comment\IndexPostCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPostCommentController extends Controller
{
    /**
     * @param  int  $postId
     * @param  IndexPostCommentAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        int $postId,
        IndexPostCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($postId);

        return CommentResource::collection($comments);
    }
}
