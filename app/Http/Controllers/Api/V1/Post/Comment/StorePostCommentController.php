<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Actions\Api\V1\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Post;

class StorePostCommentController extends Controller
{
    public function __invoke(
        Post $post,
        StoreCommentAction $action
    ): CommentResource {
        $comment = $action($post);

        return CommentResource::make($comment);
    }
}
