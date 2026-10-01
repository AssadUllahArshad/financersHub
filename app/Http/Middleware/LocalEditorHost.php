<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalEditorHost
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local') && $request->isMethod('GET') && $request->getHost() === '127.0.0.1' && ($request->is('admin', 'admin/*', 'login'))) {
            $port = $request->getPort();

            return redirect($request->getScheme().'://localhost'.(in_array($port, [80, 443]) ? '' : ':'.$port).$request->getRequestUri());
        }

        return $next($request);
    }
}
