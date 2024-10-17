<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        App::setLocale($request->header(key: 'Accept-Language', default: 'en'));

        return $next($request);
    }
}
