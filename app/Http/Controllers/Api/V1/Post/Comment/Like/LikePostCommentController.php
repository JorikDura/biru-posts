<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment\Like;

use App\Actions\Api\V1\Like\LikeAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LikeResource;
use App\Models\Comment;
use App\Models\Post;
use Exception;

class LikePostCommentController extends Controller
{
    /**
     * @param  Post  $post
     * @param  Comment  $comment
     * @param  LikeAction  $action
     * @return LikeResource
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        Comment $comment,
        LikeAction $action
    ): LikeResource {
        $likesCount = $action($comment);

        return LikeResource::make($likesCount);
    }
}
