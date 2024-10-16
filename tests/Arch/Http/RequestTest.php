<?php

use Illuminate\Foundation\Http\FormRequest;

arch('requests')
    ->expect('App\Http\Requests')
    ->toBeClasses()
    ->toExtend(FormRequest::class)
    ->toHaveSuffix('Request');
