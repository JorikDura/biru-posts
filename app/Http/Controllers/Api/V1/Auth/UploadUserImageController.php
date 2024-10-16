<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Images\DeleteImageAction;
use App\Actions\Images\StoreImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UploadUserImageRequest;
use App\Http\Resources\Api\V1\ImageResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use ReflectionException;

class UploadUserImageController extends Controller
{
    /**
     * @param  User  $user
     * @param  UploadUserImageRequest  $request
     * @param  StoreImageAction  $storeImageAction
     * @param  DeleteImageAction  $deleteImageAction
     * @return ImageResource
     * @throws ReflectionException
     */
    public function __invoke(
        #[CurrentUser] User $user,
        UploadUserImageRequest $request,
        StoreImageAction $storeImageAction,
        DeleteImageAction $deleteImageAction
    ): ImageResource {
        $deleteImageAction($user);

        $image = $storeImageAction->store(
            file: $request->validated('image'),
            model: $user
        );

        return ImageResource::make($image);
    }
}
