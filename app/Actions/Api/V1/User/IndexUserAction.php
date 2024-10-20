<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexUserAction
{
    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters('username')
            ->with('image')
            ->paginate()
            ->appends(request()->query());
    }
}
