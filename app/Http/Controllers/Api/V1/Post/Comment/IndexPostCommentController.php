<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Actions\Api\V1\Comment\IndexCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPostCommentController extends Controller
{
    /**
     * @param  Post  $post
     * @param  IndexCommentAction  $action
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        IndexCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($post);

        return CommentResource::collection($comments);
    }
}
