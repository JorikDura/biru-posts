<?php

declare(strict_types=1);

namespace App\Actions\Model;

use App\Actions\Images\DeleteImageAction;
use Illuminate\Database\Eloquent\Model;
use ReflectionException;

final readonly class PurgeModelAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    /**
     * @param  Model  $model
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Model $model): void
    {
        $this->deleteImageAction->__invoke($model);

        $model->delete();
    }
}
