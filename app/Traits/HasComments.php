<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function deleteComments(): void
    {
        /** @var Collection<array-key, Comment> $comments */
        $comments = $this->comments()->get();

        $comments->each(function (Comment $comment) {
            $comment->deleteImages();
        });

        $this->comments()->delete();
    }
}
