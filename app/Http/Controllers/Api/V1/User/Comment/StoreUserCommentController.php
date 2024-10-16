<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Api\V1\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\User;

class StoreUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  StoreCommentAction  $action
     * @return CommentResource
     */
    public function __invoke(
        User $user,
        StoreCommentAction $action
    ): CommentResource {
        $comment = $action($user);

        return CommentResource::make($comment);
    }
}
