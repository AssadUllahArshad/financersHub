<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VisitorAnalyticsController extends Controller {
    public function index(Request $request) {
        $days = (int)$request->query('days',30); if (!in_array($days,[7,30,90],true)) { $days=30; }
        $query = DB::table('visitor_traces')->where('visited_at','>=',now()->subDays($days-1)->startOfDay());
        $views=(clone $query)->count(); $visitors=(clone $query)->distinct()->count('visitor_hash');
        $pages=(clone $query)->select('path')->selectRaw('COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')->groupBy('path')->orderByDesc('views')->limit(20)->get();
        $referrers=(clone $query)->select('referrer_host')->selectRaw('COUNT(*) as views')->groupBy('referrer_host')->orderByDesc('views')->limit(10)->get();
        $devices=(clone $query)->select('device','browser')->selectRaw('COUNT(*) as views')->groupBy('device','browser')->orderByDesc('views')->get();
        $recent=(clone $query)->orderByDesc('visited_at')->paginate(25)->withQueryString();
        return view('cms.analytics',compact('days','views','visitors','pages','referrers','devices','recent'));
    }
}
