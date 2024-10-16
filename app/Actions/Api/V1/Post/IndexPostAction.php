<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPostAction
{
    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(Post::class)
            ->allowedFilters(AllowedFilter::exact('user', 'user_id'))
            ->with([
                'user:id,username' => ['image'],
                'images',
                'tags'
            ])
            ->paginate()
            ->appends(request()->query());
    }
}
