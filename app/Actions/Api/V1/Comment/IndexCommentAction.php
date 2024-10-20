<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexCommentAction
{
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
            ->paginate()
            ->appends(request()->query());
    }
}
