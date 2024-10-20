<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserImageController extends Controller
{
    /**
     * @param  User  $user
     * @return Response
     */
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $user->deleteImage();

        return response()->noContent();
    }
}
