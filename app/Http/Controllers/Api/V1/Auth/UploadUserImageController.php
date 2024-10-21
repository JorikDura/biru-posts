<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\User\UploadUserImageAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ImageResource;
use ReflectionException;

class UploadUserImageController extends Controller
{
    /**
     * @param  UploadUserImageAction  $action
     * @return ImageResource
     * @throws ReflectionException
     */
    public function __invoke(UploadUserImageAction $action): ImageResource
    {
        $image = $action();

        return ImageResource::make($image);
    }
}
