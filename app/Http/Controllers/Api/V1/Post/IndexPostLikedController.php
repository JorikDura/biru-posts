<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\IndexPostLikedAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPostLikedController extends Controller
{
    /**
     * @param  Post  $post
     * @param  IndexPostLikedAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Post $post,
        IndexPostLikedAction $action
    ): AnonymousResourceCollection {
        $users = $action($post);

        return UserResource::collection($users);
    }
}
