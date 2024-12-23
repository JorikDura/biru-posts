<?php

use App\Http\Controllers\Api\V1\Post\Comment\DeletePostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\IndexPostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\Like\LikePostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\StorePostCommentController;
use App\Http\Controllers\Api\V1\Post\DeletePostController;
use App\Http\Controllers\Api\V1\Post\IndexPostController;
use App\Http\Controllers\Api\V1\Post\Like\IndexPostLikedController;
use App\Http\Controllers\Api\V1\Post\Like\LikePostController;
use App\Http\Controllers\Api\V1\Post\ShowPostController;
use App\Http\Controllers\Api\V1\Post\StorePostController;
use App\Http\Controllers\Api\V1\Post\UpdatePostController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/posts'], function () {
    Route::get('/', IndexPostController::class);
    Route::get('/{post}', ShowPostController::class);
    Route::get('/{post}/liked', IndexPostLikedController::class);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', StorePostController::class);
        Route::group(['prefix' => '/{post}'], function () {
            Route::post('/like', LikePostController::class);
            Route::match(['PUT', 'PATCH'], '/', UpdatePostController::class)
                ->can('update', 'post');
            Route::delete('/', DeletePostController::class)
                ->can('delete', 'post');
        });
    });

    Route::prefix('{post}/comments')->group(function () {
        Route::get('/', IndexPostCommentController::class);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', StorePostCommentController::class);
            Route::prefix('/{comment}')->group(function () {
                Route::post('/like', LikePostCommentController::class);
                Route::delete('/', DeletePostCommentController::class)
                    ->can('delete', 'comment');
            });
        });
    });
});
