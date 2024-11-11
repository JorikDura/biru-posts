<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Like;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

/**
 * Toggles like on model
 */
final readonly class LikeAction
{
    private const string ERROR_MESSAGE = "There's no 'likes' method in %s model.";

    public function __construct(
        #[CurrentUser] private User $user
    ) {
    }

    /**
     * @param  Model  $model
     * @return int
     * @throws RuntimeException
     */
    public function __invoke(Model $model): int
    {
        if (!method_exists($model, 'likes')) {
            throw new RuntimeException(
                message: sprintf(self::ERROR_MESSAGE, $model::class),
            );
        }

        $model->likes()->toggle($this->user);

        return $model->likes()->count();
    }
}
