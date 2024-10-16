<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Tag;

use App\Actions\Api\V1\Tag\IndexTagAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TagResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexTagController extends Controller
{
    /**
     * @param  IndexTagAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(IndexTagAction $action): AnonymousResourceCollection
    {
        $tags = $action();

        return TagResource::collection($tags);
    }
}
