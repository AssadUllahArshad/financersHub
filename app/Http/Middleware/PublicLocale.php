<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicLocale
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->routeIs('es.*') ? 'es' : 'en');
        $response = $next($request);
        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }
}
