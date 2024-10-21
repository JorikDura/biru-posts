<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\Like\LikePostAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;

class LikePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  LikePostAction  $action
     * @return Response
     */
    public function __invoke(
        Post $post,
        LikePostAction $action
    ): Response {
        $action($post);

        return response()->noContent();
    }
}
