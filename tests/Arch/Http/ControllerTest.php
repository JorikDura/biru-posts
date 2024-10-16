<?php

use App\Http\Controllers\Controller;

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toBeClasses()
    ->toExtend(Controller::class)
    ->toHaveSuffix('Controller')
    ->ignoring(Controller::class)
    ->toUseStrictTypes();

arch('base controller')
    ->expect(Controller::class)
    ->toBeAbstract();
