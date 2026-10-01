<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RecordVisitor {
    public function handle(Request $request, Closure $next) {
        $response = $next($request);
        $agent = $request->userAgent() ?? '';
        if (!config('financershub.analytics_enabled') || !$request->isMethod('GET') || $response->getStatusCode() !== 200
            || !str_contains($response->headers->get('Content-Type',''), 'text/html') || $request->user()
            || $request->is('admin','admin/*','login','media/*') || $request->header('DNT') === '1' || $request->header('Sec-GPC') === '1'
            || !$agent || preg_match('/bot|crawl|spider|headless|lighthouse|preview|uptime/i',$agent)) { return $response; }
        try {
            $ip = inet_pton($request->ip() ?? '');
            if ($ip === false || !config('app.key')) { return $response; }
            $visitor = hash_hmac('sha256', $ip, config('app.key'));
            $path = mb_substr($request->getPathInfo(),0,500);
            $host = parse_url($request->header('referer',''), PHP_URL_HOST);
            $browser = preg_match('/Edg/i',$agent) ? 'Edge' : (preg_match('/Firefox/i',$agent) ? 'Firefox' : (preg_match('/Chrome|CriOS/i',$agent) ? 'Chrome' : (preg_match('/Safari/i',$agent) ? 'Safari' : 'Other')));
            DB::table('visitor_traces')->insertOrIgnore([
                'visitor_hash'=>$visitor, 'dedup_key'=>hash('sha256',$visitor.'|'.$path.'|'.now()->format('Y-m-d H:i')),
                'path'=>$path, 'referrer_host'=>$host ? mb_substr(strtolower($host),0,255) : null,
                'device'=>preg_match('/iPad|Tablet/i',$agent) ? 'Tablet' : (preg_match('/Mobile|Android|iPhone/i',$agent) ? 'Mobile' : 'Desktop'),
                'browser'=>$browser, 'visited_at'=>now(),
            ]);
        } catch (\Throwable $error) { report($error); }
        return $response;
    }
}
