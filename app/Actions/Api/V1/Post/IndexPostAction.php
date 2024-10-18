<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Http\Requests\Api\V1\Post\IndexPostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPostAction
{
    public function __construct(
        private IndexPostRequest $request,
        #[CurrentUser('sanctum')] private ?User $user
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(Post::class)
            ->allowedFilters([
                AllowedFilter::exact(
                    name: 'user_creator_id',
                    internalName: 'user_id'
                ),
                AllowedFilter::callback(
                    name: 'user_liked_id',
                    callback: fn (EloquentBuilder $query, int $value) => $this->joinLikes($query, $value)
                ),
                AllowedFilter::callback(
                    name: 'is_liked',
                    callback: fn (EloquentBuilder $query, bool $value) => $query->when(
                        value: $value,
                        callback: fn (EloquentBuilder $query) => $this->joinLikes($query, $this->user?->id)
                    )
                )
            ])
            ->addSelect([
                'id',
                'posts.user_id',
                'text',
                'created_at',
                'updated_at'
            ])
            ->with([
                'user:id,username' => ['image'],
                'images',
                'tags'
            ])
            ->selectSub(
                query: fn (Builder $query) => $query->selectRaw('count(*)')
                    ->from('like_post')
                    ->whereRaw('"like_post"."post_id" = "posts"."id"'),
                as: 'likes_count'
            )
            ->when(
                value: !is_null($this->user?->id),
                callback: fn (EloquentBuilder $query) => $query->selectSub(
                    query: fn (Builder $query) => $query
                        ->selectRaw('case count(*) when 0 then false else true end')
                        ->from('like_post')
                        ->whereRaw(
                            sql: '"like_post"."post_id" = "posts"."id" AND "like_post"."user_id" = ?',
                            bindings: [$this->user->id]
                        ),
                    as: 'is_liked'
                )
            )
            ->paginate()
            ->appends($this->request->query());
    }

    private function joinLikes(EloquentBuilder $query, int $value): void
    {
        $query->join(
            table: 'like_post',
            first: 'id',
            operator: '=',
            second: 'post_id'
        )->whereRaw(
            sql: '"like_post"."user_id" = ?',
            bindings: [$value]
        );
    }
}
