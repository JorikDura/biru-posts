<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;

class ShowPostController extends Controller
{
    /**
     * @param  Post  $post
     * @return PostResource
     */
    public function __invoke(Post $post): PostResource
    {
        return PostResource::make($post->loadFull());
    }
}
