<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;

class UpdatePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  UpdatePostAction  $action
     * @return PostResource
     */
    public function __invoke(
        Post $post,
        UpdatePostAction $action
    ): PostResource {
        $post = $action($post);

        return PostResource::make($post);
    }
}
