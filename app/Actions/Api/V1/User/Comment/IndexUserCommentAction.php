<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User\Comment;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexUserCommentAction
{
    /**
     * @param  User  $user
     * @return LengthAwarePaginator
     */
    public function __invoke(User $user): LengthAwarePaginator
    {
        return $user->comments()
            ->with([
                'images',
                'user:id,username' => ['image']
            ])->paginate()->appends(request()->query());
    }
}
