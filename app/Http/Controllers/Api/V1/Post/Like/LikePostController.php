<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Api\V1\Like\LikeAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LikeResource;
use App\Models\Post;
use Exception;

class LikePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  LikeAction  $action
     * @return LikeResource
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        LikeAction $action
    ): LikeResource {
        $likesCount = $action($post);

        return LikeResource::make($likesCount);
    }
}
