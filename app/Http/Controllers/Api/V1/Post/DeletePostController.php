<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;

class DeletePostController extends Controller
{
    /**
     * @param  Post  $post
     * @return Response
     */
    public function __invoke(
        Post $post
    ): Response {
        $post->delete();

        return response()->noContent();
    }
}
