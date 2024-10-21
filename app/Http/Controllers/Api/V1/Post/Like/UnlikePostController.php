<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Api\V1\Post\Like\UnlikePostAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;

class UnlikePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  UnlikePostAction  $action
     * @return Response
     */
    public function __invoke(
        Post $post,
        UnlikePostAction $action
    ): Response {
        $action($post);

        return response()->noContent();
    }
}
