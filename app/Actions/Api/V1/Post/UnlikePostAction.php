<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class UnlikePostAction
{
    public function __construct(
        #[CurrentUser] private User $user
    ) {
    }

    /**
     * @param  Post  $post
     * @return void
     */
    public function __invoke(Post $post): void
    {
        $post->likes()->detach($this->user);
    }
}
