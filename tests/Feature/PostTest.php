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

        //коммент для поста
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        //картинки для поста
        $originalPostImage = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-post.jpg'
        );

        $previewPostImage = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-post-scaled.jpg'
        );

        //картинки для комментария
        $originalPostCommentImage = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-post.jpg'
        );

        $previewPostCommentImage = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-post-scaled.jpg'
        );

        /** @var Image $postImage */
        $postImage = Image::factory()->create([
            'imageable_id' => $post->id,
            'imageable_type' => Post::class,
            'original_image' => $originalPostImage,
            'preview_image' => $previewPostImage
        ]);

        /** @var Image $postCommentImage */
        $postCommentImage = Image::factory()->create([
            'imageable_id' => $post->id,
            'imageable_type' => Post::class,
            'original_image' => $originalPostCommentImage,
            'preview_image' => $previewPostCommentImage
        ]);

        Storage::disk('public')->assertExists([
            $postImage->original_image,
            $postImage->preview_image,
        ]);

        Storage::disk('public')->assertExists([
            $postCommentImage->original_image,
            $postCommentImage->preview_image,
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
            data: $postImage->toArray()
        );

        assertDatabaseMissing(
            table: 'comments',
            data: $comment->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $postCommentImage->toArray()
        );

        Storage::disk('public')->assertMissing([
            $postImage->original_image,
            $postImage->preview_image,
            $postCommentImage->original_image,
            $postCommentImage->preview_image
        ]);
    });

    it('delete another user post', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        actingAs($this->user)
            ->deleteJson("api/v1/posts/$post->id")
            ->assertForbidden();

        assertDatabaseHas(
            table: 'posts',
            data: $post->toArray()
        );
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
            ->assertSee([
                'likes_count'
            ]);

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

        $post->likes()->toggle($this->user->id);

        actingAs($this->user)
            ->postJson("api/v1/posts/$post->id/like")
            ->assertSuccessful()
            ->assertSee([
                'likes_count'
            ]);

        assertDatabaseMissing(
            table: 'like_post',
            data: [
                'user_id' => $this->user->id,
                'post_id' => $post->id,
            ]
        );
    });

    it('get user liked posts', function () {
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

    it('get liked users', function () {
        /** @var Post $post */
        $post = Post::factory()->create();

        $post->likes()->attach($this->user);

        getJson("api/v1/posts/$post->id/liked")
            ->assertSuccessful()
            ->assertSee([
                'id' => $this->user->id,
                'username' => $this->user->username
            ]);
    });

    it('like post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id/comments/$comment->id/like",
            )
            ->assertSuccessful()
            ->assertSee([
                'likes_count'
            ]);

        assertDatabaseHas(
            table: 'like_comment',
            data: [
                'user_id' => $this->user->id,
                'comment_id' => $comment->id
            ]
        );
    });

    it('unlike post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        $comment->likes()->sync($this->user);

        actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id/comments/$comment->id/like",
            )
            ->assertSuccessful()
            ->assertSee([
                'likes_count'
            ]);

        assertDatabaseMissing(
            table: 'like_comment',
            data: [
                'user_id' => $this->user->id,
                'comment_id' => $comment->id
            ]
        );
    });

    it('store post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $data = [
            'text' => fake()->text(),
            'images' => TestHelpers::randomUploadedFiles(max: 5)
        ];

        $test = actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id/comments",
                data: $data
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'user_id' => $this->user->id,
                'commentable_id' => $post->id,
                'commentable_type' => Post::class,
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

    it('store related comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        $data = [
            'comment_id' => $comment->id,
            'text' => fake()->text()
        ];

        actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/$post->id/comments",
                data: $data
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'user_id' => $this->user->id,
                'comment_id' => $comment->id,
                'commentable_id' => $post->id,
                'commentable_type' => Post::class,
                'text' => $data['text']
            ]
        );
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

    it('delete another user post comment', function () {
        /** @var Post $post */

        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text()
        ]);

        actingAs($this->user)
            ->deleteJson(
                uri: "api/v1/posts/$post->id/comments/$comment->id"
            )->assertForbidden();

        assertDatabaseHas(
            table: 'comments',
            data: $comment->toArray()
        );
    });
});
