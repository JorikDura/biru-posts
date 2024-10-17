<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\ShowPostAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;

class ShowPostController extends Controller
{
    /**
     * @param  int  $postId
     * @param  ShowPostAction  $action
     * @return PostResource
     */
    public function __invoke(
        int $postId,
        ShowPostAction $action
    ): PostResource {
        $post = $action($postId);

        return PostResource::make($post);
    }
}
