<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Model\PurgeModelAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;
use ReflectionException;

class DeletePostController extends Controller
{
    /**
     * @param  Post  $post
     * @param  PurgeModelAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Post $post,
        PurgeModelAction $action
    ): Response {
        $action($post);

        return response()->noContent();
    }
}
