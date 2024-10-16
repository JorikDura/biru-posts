<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class ShowUserController extends Controller
{
    /**
     * @param  User  $user
     * @return UserResource
     */
    public function __invoke(User $user): UserResource
    {
        return UserResource::make($user->load(['image']));
    }
}
