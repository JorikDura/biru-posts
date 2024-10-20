<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Api\V1\Comment\IndexCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  IndexCommentAction  $action
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function __invoke(
        User $user,
        IndexCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($user);

        return CommentResource::collection($comments);
    }
}
