<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\StorePostAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;

class StorePostController extends Controller
{
    /**
     * @param  StorePostAction  $action
     * @return PostResource
     */
    public function __invoke(StorePostAction $action): PostResource
    {
        $post = $action();

        return PostResource::make($post);
    }
}
