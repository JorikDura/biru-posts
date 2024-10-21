<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexCommentAction
{
    public function __construct(
        #[CurrentUser('sanctum')] private ?User $user
    ) {
    }

    private const string ERROR_MESSAGE = "There's no 'comments' method in %s model.";

    /**
     * @param  Model  $model
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function __invoke(Model $model): LengthAwarePaginator
    {
        if (!method_exists($model, 'comments')) {
            throw new Exception(
                message: sprintf(self::ERROR_MESSAGE, $model::class),
            );
        }

        return $model->comments()
            ->with([
                'images',
                'user:id,username' => ['image']
            ])
            ->addSelect([
                'id',
                'user_id',
                'comment_id',
                'text',
                'created_at',
                'updated_at'
            ])
            ->selectSub(
                query: fn (Builder $query) => $query->selectRaw('count(*)')
                    ->from('like_comment')
                    ->whereRaw('"like_comment"."comment_id" = "comments"."id"'),
                as: 'likes_count'
            )
            ->when(
                value: !is_null($this->user?->id),
                callback: fn (EloquentBuilder $query) => $query->selectSub(
                    query: fn (Builder $query) => $query
                        ->selectRaw('case count(*) when 0 then false else true end')
                        ->from('like_comment')
                        ->whereRaw(
                            sql: '"like_comment"."comment_id" = "comments"."id" AND "like_comment"."user_id" = ?',
                            bindings: [$this->user->id]
                        ),
                    as: 'is_liked'
                )
            )
            ->paginate()
            ->appends(request()->query());
    }
}
