<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User;

use App\Http\Requests\Api\V1\Auth\UploadUserImageRequest;
use App\Models\Image;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use ReflectionException;

final readonly class UploadUserImageAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private UploadUserImageRequest $request
    ) {
    }

    /**
     * @return Image
     * @throws ReflectionException
     */
    public function __invoke(): Image
    {
        $this->user->deleteImage();

        return Image::create(
            file: $this->request->validated('image'),
            model: $this->user
        );
    }
}
