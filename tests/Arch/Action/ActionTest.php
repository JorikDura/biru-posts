<?php

arch('actions test')
    ->expect('App\Actions')
    ->toBeClasses()
    ->toBeFinal()
    ->toBeReadonly()
    ->toUseStrictTypes()
    ->toHaveSuffix('Action');
