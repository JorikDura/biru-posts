<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function deleteImages(): void
    {
        /** @var Collection<array-key, Image> $images */
        $images = $this->images()->get();

        $images->each(function (Image $image) {
            $image->deleteImagesInStorage();
        });

        $this->images()->delete();
    }
}
