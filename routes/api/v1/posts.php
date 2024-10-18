<?php

use App\Http\Controllers\Api\V1\Post\Comment\DeletePostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\IndexPostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\StorePostCommentController;
use App\Http\Controllers\Api\V1\Post\DeletePostController;
use App\Http\Controllers\Api\V1\Post\IndexPostController;
use App\Http\Controllers\Api\V1\Post\IndexPostLikedController;
use App\Http\Controllers\Api\V1\Post\LikePostController;
use App\Http\Controllers\Api\V1\Post\ShowPostController;
use App\Http\Controllers\Api\V1\Post\StorePostController;
use App\Http\Controllers\Api\V1\Post\UnlikePostController;
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
            Route::post('/unlike', UnlikePostController::class);
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
            Route::delete('/{comment}', DeletePostCommentController::class)
                ->can('delete', 'comment');
        });
    });
});
