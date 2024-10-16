<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Tag;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexTagAction
{
    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(Tag::class)
            ->allowedFilters('name')
            ->select(['tags.id', 'tags.name'])
            ->join(
                table: 'post_tag',
                first: 'id',
                operator: '=',
                second: 'tag_id'
            )
            ->groupBy(['tags.id', 'tags.name'])
            ->orderByRaw('count(post_tag.*) DESC')
            ->paginate()
            ->appends(request()->query());
    }
}
