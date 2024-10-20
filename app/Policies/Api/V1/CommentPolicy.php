<?php

declare(strict_types=1);

namespace App\Policies\Api\V1;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        return ($user->isAdmin() || $user->isModerator())
            ? true
            : null;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $comment->isCommentableUser($user->id);
    }
}
