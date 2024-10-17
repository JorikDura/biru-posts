<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

final readonly class ShowPostAction
{
    public function __invoke(int $postId): Post
    {
        /** @var ?User $user */
        $user = auth('sanctum')->user();

        return Post::with([
            'tags',
            'images',
            'user:id,username' => ['image']
        ])->addSelect('*')->selectSub(
            query: fn (Builder $query) => $query->selectRaw('count(*)')
                ->from('like_post')
                ->whereRaw('"like_post"."post_id" = "posts"."id"'),
            as: 'likes_count'
        )->when(
            value: !is_null($user),
            callback: fn (EloquentBuilder $query) => $query->selectSub(
                query: fn (Builder $query) => $query
                    ->selectRaw('case count(*) when 0 then false else true end')
                    ->from('like_post')
                    ->whereRaw(
                        sql: '"like_post"."post_id" = "posts"."id" AND "like_post"."user_id" = ?',
                        bindings: [$user->id]
                    ),
                as: 'is_liked'
            )
        )->findOrFail($postId);
    }
}
