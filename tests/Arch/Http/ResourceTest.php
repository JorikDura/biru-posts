<?php

use Illuminate\Http\Resources\Json\JsonResource;

arch('resources')
    ->expect('App\Http\Resources')
    ->toBeClasses()
    ->toExtend(JsonResource::class)
    ->toHaveSuffix('Resource');
