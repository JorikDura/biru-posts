<?php

use App\Models\Post;
use App\Models\Tag;

use function Pest\Laravel\getJson;

describe('tags test', function () {
    beforeEach(function () {
        $post = Post::factory()->create();
        $this->tags = Tag::factory(15)->create();

        $post->tags()->attach($this->tags);
    });

    it('get tags', function () {
        /** @var Tag $tag */
        $tag = $this->tags->random();

        getJson('api/v1/tags')
            ->assertSuccessful()
            ->assertSee($tag->toArray());
    });

    it('get tag via filter', function () {
        /** @var Tag $tag */
        $tag = $this->tags->random();

        getJson("api/v1/tags?filter[name]=$tag->name")
            ->assertSuccessful()
            ->assertSee($tag->toArray());
    });
});
