<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final readonly class StoreCommentAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private StoreCommentRequest $request
    ) {
    }

    /**
     * @param  Model  $model
     * @return Comment
     */
    public function __invoke(Model $model): Comment
    {
        return DB::transaction(function () use ($model) {
            $comment = Comment::create([
                'user_id' => $this->user->id,
                'commentable_id' => $model->getKey(),
                'commentable_type' => $model::class,
                'text' => $this->request->validated('text')
            ]);

            $this->request->whenHas('images', function (array $images) use ($comment) {
                Image::insert(
                    files: $images,
                    model: $comment
                );
            });

            return $comment
                ->load([
                    'user:id,username' => ['image'],
                    'images'
                ]);
        });
    }
}
