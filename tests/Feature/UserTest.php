<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;

describe('users tests', function () {
    beforeEach(function () {
        $this->users = User::factory(15)->create();
    });

    it('get users', function () {
        /** @var User $user */
        $user = $this->users->random();

        getJson('api/v1/users')
            ->assertSuccessful()
            ->assertSee([
                'id' => $user->id,
                'username' => $user->username
            ]);
    });

    it('get user by id', function () {
        /** @var User $user */
        $user = $this->users->random();

        getJson("api/v1/users/$user->id")
            ->assertSuccessful()
            ->assertSee([
                'id' => $user->id,
                'username' => $user->username
            ]);
    });

    it('get user comments', function () {
        /** @var User $user */
        $user = $this->users->random();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $user->id,
            'commentable_type' => User::class
        ]);

        getJson("api/v1/users/$user->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'text' => $comment->text,
            ]);
    });

    it('store user comments', function () {
        $user = $this->users->random();

        $data = [
            'text' => fake()->text()
        ];

        $newUser = User::factory()->create();

        actingAs($newUser)
            ->postJson(
                uri: "api/v1/users/$user->id/comments",
                data: $data
            )->assertSuccessful()->assertSee([
                'text' => $data['text']
            ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'text' => $data['text'],
                'user_id' => $newUser->id,
                'commentable_id' => $user->id,
                'commentable_type' => User::class
            ]
        );
    });
});
