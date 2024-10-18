<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPostLikedAction
{
    /**
     * @param  Post  $post
     * @return LengthAwarePaginator
     */
    public function __invoke(Post $post): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters(['username'])
            ->join(
                table: 'like_post',
                first: 'id',
                operator: '=',
                second: 'user_id'
            )
            ->where('like_post.post_id', $post->id)
            ->with(['image:id,imageable_id,original_image,preview_image'])
            ->paginate(columns: ['id', 'username'])
            ->appends(request()->query());
    }
}
