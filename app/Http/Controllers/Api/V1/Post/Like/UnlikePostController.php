<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Api\V1\Like\UnlikeAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LikeResource;
use App\Models\Post;
use Exception;

class UnlikePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  UnlikeAction  $action
     * @return LikeResource
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        UnlikeAction $action
    ): LikeResource {
        $likesCount = $action($post);

        return LikeResource::make($likesCount);
    }
}
