<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicationHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // These restrictions work with the existing TinyMCE and local assets.
        $response->headers->set('Content-Security-Policy', "base-uri 'self'; object-src 'none'; frame-ancestors 'self'");
        if ($request->is('admin', 'admin/*', 'login', 'logout') || $request->user()) {
            $response->headers->set('Cache-Control', 'private, no-store');
        }

        return $response;
    }
}
