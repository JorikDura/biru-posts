<?php

use App\Http\Controllers\Api\V1\User\Comment\DeleteUserCommentController;
use App\Http\Controllers\Api\V1\User\Comment\IndexUserCommentController;
use App\Http\Controllers\Api\V1\User\Comment\Like\LikeUserCommentController;
use App\Http\Controllers\Api\V1\User\Comment\StoreUserCommentController;
use App\Http\Controllers\Api\V1\User\IndexUserController;
use App\Http\Controllers\Api\V1\User\ShowUserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/users'], function () {
    Route::get('/', IndexUserController::class);
    Route::get('/{user}', ShowUserController::class);
    Route::prefix('/{user}/comments')->group(function () {
        Route::get('/', IndexUserCommentController::class);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', StoreUserCommentController::class);
            Route::prefix('/{comment}')->group(function () {
                Route::post('/like', LikeUserCommentController::class);
                Route::delete('/', DeleteUserCommentController::class)
                    ->can('delete', 'comment');
            });
        });
    });
});
