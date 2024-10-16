<?php

use App\Http\Controllers\Api\V1\Tag\IndexTagController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/tags'], function () {
    Route::get('/', IndexTagController::class);
});
