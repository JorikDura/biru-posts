<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Http\Requests\Api\V1\Post\UpdatePostRequest;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;

final readonly class UpdatePostAction
{
    public function __construct(
        private UpdatePostRequest $request
    ) {
    }

    /**
     * @param  Post  $post
     * @return Post
     */
    public function __invoke(Post $post): Post
    {
        $this->request->whenHas('text', function (string $text) use ($post) {
            $post->update([
                'text' => $text
            ]);
        });

        $this->request->whenHas('tags', function (array $tags) use ($post) {
            $tagsIds = collect($tags)->map(function (string $tag) {
                return Tag::firstOrCreate([
                    'name' => $tag
                ])->id;
            });
            $post->tags()->sync($tagsIds);
        });

        $this->request->whenHas('images', fn (array $images) => Image::insert(
            files: $images,
            model: $post
        ));

        return $post->loadFull();
    }
}
