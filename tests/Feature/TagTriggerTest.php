<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('tag delete trigger test', function () {
    $post = Post::factory()
        ->create();

    /** @var Collection<Tag> $tags */
    $tags = Tag::factory(3)->create();

    $post->tags()->attach($tags);

    /** @var Tag $randomTag */
    $randomTag = $tags->random();

    assertDatabaseHas(
        table: 'post_tag',
        data: [
            'post_id' => $post->id,
            'tag_id' => $randomTag->id
        ]
    );

    $tags = $tags->except($randomTag->id);

    $post->tags()->sync($tags);

    assertDatabaseMissing(
        table: 'post_tag',
        data: [
            'post_id' => $post->id,
            'tag_id' => $randomTag->id
        ]
    );

    assertDatabaseMissing(
        table: 'tags',
        data: $randomTag->toArray()
    );
});
