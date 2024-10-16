<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post\Comment;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexPostCommentAction
{
    /**
     * @param  int  $postId
     * @return LengthAwarePaginator
     */
    public function __invoke(int $postId): LengthAwarePaginator
    {
        return Comment::where([
            'commentable_id' => $postId,
            'commentable_type' => Post::class,
        ])->with([
            'images',
            'user:id,username' => ['image']
        ])->paginate()->appends(request()->query());
    }
}
