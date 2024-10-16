<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Image
 */
class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original_image_url' => asset("storage/$this->original_image"),
            'preview_image_url' => !is_null($this->preview_image)
                ? asset("storage/$this->preview_image")
                : asset("storage/$this->original_image")
        ];
    }
}
