<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Api\V1\Like\UnlikeAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Response;

class UnlikePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  UnlikeAction  $action
     * @return Response
     * @throws Exception
     */
    public function __invoke(
        Post $post,
        UnlikeAction $action
    ): Response {
        $action($post);

        return response()->noContent();
    }
}
