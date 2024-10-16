<?php

use App\Http\Controllers\Api\V1\Auth\DeleteUserImageController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\UploadUserImageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/auth'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('/login', LoginController::class);
        Route::post('/registration', RegistrationController::class);
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', LogoutController::class);
        Route::group(['prefix' => 'image'], function () {
            Route::post('/', UploadUserImageController::class);
            Route::delete('/', DeleteUserImageController::class);
        });
    });
});
