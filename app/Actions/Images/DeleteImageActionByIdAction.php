<?php

declare(strict_types=1);

namespace App\Actions\Images;

use App\Http\Requests\Api\V1\Image\DeleteImageRequest;
use App\Models\Image;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class DeleteImageActionByIdAction
{
    public function __construct(
        private DeleteImageRequest $request
    ) {
    }

    /**
     * Deletes given model images via id
     * @param  mixed  $model
     * @return void
     */
    public function __invoke(mixed $model): void
    {
        /** @var array $imageIds */
        $imageIds = $this->request->validated('id');

        /** @var Collection<Image> $images */
        $images = $model->images()->find($imageIds);

        abort_if(
            boolean: $images->isEmpty(),
            code: Response::HTTP_NOT_FOUND,
            message: __('messages.no_image_found_for_current_model')
        );

        $images->each(fn (Image $image) => $image->deleteImagesInStorage());

        $model->images()
            ->whereId($imageIds)
            ->delete();
    }
}
