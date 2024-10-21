<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Like;

use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Model;

final readonly class UnlikeAction
{
    private const string ERROR_MESSAGE = "There's no 'likes' method in %s model.";

    public function __construct(
        #[CurrentUser] private User $user
    ) {
    }

    /**
     * @param  Model  $model
     * @return void
     * @throws Exception
     */
    public function __invoke(Model $model): void
    {
        if (!method_exists($model, 'likes')) {
            throw new Exception(
                message: sprintf(self::ERROR_MESSAGE, $model::class),
            );
        }

        $model->likes()->detach($this->user);
    }
}
