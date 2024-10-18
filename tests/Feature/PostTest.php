<?php

use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('posts tests', function () {
    beforeEach(function () {
        $this->posts = Post::factory(15)->create();
        $this->user = User::factory()->create();
    });

    it('get posts', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        getJson('api/v1/posts')
            ->assertSuccessful()
            ->assertSee($post->toArray());
    });

    it('store post', function () {
        $data = [
            'text' => fake('en_GB')->text(),
            'images' => TestHelpers::randomUploadedFiles(max: 10),
            'tags' => [fake('en_GB')->word()]
        ];

        $test = actingAs($this->user)
            ->postJson(
                uri: 'api/v1/posts',
                data: $data
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'posts',
            data: [
                'text' => $data['text']
            ]
        );

        /** @var Post $author */
        $post = $test->original;

        /** @var Image $image */
        $image = $post->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('update post', function () {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $data = [
            'text' => fake('en_GB')->text(),
            'images' => TestHelpers::randomUploadedFiles(max: 3),
            'tags' => [fake('en_GB')->word()]
        ];

        $test = actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id",
                data: $data + ['_method' => 'PATCH']
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'posts',
            data: [
                'text' => $data['text']
            ]
        );

        /** @var Post $author */
        $post = $test->original;

        /** @var Image $image */
        $image = $post->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('delete post', function () {
        /** @var Post $post */
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $original = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test.jpg'
        );

        $preview = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-scaled.jpg'
        );

        $imageData = [
            'imageable_id' => $post->id,
            'imageable_type' => Post::class,
            'original_image' => $original,
            'preview_image' => $preview
        ];

        /** @var Image $image */
        $image = Image::factory()->create($imageData);

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image,
        ]);

        actingAs($this->user)
            ->deleteJson("api/v1/posts/$post->id")
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseMissing(
            table: 'posts',
            data: $post->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $imageData
        );

        Storage::disk('public')->assertMissing([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('get post comments', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        getJson("api/v1/posts/$post->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'text' => $comment->text
            ]);
    });

    it('like post', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        actingAs($this->user)
            ->postJson("api/v1/posts/$post->id/like")
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseHas(
            table: 'like_post',
            data: [
                'user_id' => $this->user->id,
                'post_id' => $post->id,
            ]
        );
    });

    it('unlike post', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        actingAs($this->user)
            ->postJson("api/v1/posts/$post->id/unlike")
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseMissing(
            table: 'like_post',
            data: [
                'user_id' => $this->user->id,
                'post_id' => $post->id,
            ]
        );
    });

    it('get liked posts', function () {
        /** @var Post $post */
        $post = Post::factory()->create();

        $post->likes()->attach($this->user);

        actingAs($this->user)
            ->getJson("api/v1/posts?filter[is_liked]=1")
            ->assertSuccessful()
            ->assertSee([
                'text' => $post->text
            ]);

        getJson("api/v1/posts?filter[user_liked_id]={$this->user->id}")
            ->assertSuccessful()
            ->assertSee([
                'text' => $post->text
            ]);
    });

    it('store post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ];

        $test = actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id/comments",
                data: $data + ['images' => TestHelpers::randomUploadedFiles(max: 5)]
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'comments',
            data: $data
        );

        /** @var Post $author */
        $post = $test->original;

        /** @var Image $image */
        $image = $post->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('delete post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        actingAs($this->user)
            ->deleteJson(
                uri: "api/v1/posts/$post->id/comments/$comment->id"
            )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $comment->toArray()
        );
    });
});
