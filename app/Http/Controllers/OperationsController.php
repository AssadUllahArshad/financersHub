<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Article::query()->where('is_demo', false);
        if ($request->user()->role === 'author') {
            $query->where('user_id', $request->user()->id);
        }
        $counts = (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $articles = $query->with('authorProfile')->latest('updated_at')->limit(8)->get();

        return view('cms.dashboard', compact('counts', 'articles'));
    }

    public function seo()
    {
        $this->authorize('manage-settings');

        return view('cms.seo', ['settings' => SiteSetting::pluck('value', 'key')]);
    }

    public function saveSeo(Request $request)
    {
        $this->authorize('manage-settings');
        $data = $request->validate(['seo_site_name' => 'required|string|max:80', 'seo_description' => 'required|string|max:300']);
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('status', 'SEO defaults saved. Article-specific metadata takes priority.');
    }

    public function advertising()
    {
        return view('cms.advertising', ['publisher' => SiteSetting::where('key', 'adsense_publisher_id')->value('value')]);
    }

    public function saveAdvertising(Request $request)
    {
        $this->authorize('manage-settings');
        $data = $request->validate(['adsense_publisher_id' => ['nullable', 'regex:/^pub-[0-9]{16}$/']]);
        SiteSetting::updateOrCreate(['key' => 'adsense_publisher_id'], ['value' => $data['adsense_publisher_id'] ?? null]);

        return back()->with('status', 'Publisher verification settings saved. Ads remain disabled.');
    }
}
