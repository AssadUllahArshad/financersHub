@extends('layouts.studio')
@section('live','1')
@section('title','Advertising readiness')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">MONETIZATION</span><h1>Advertising readiness</h1><p>Prepare ownership verification without loading ads or tracking visitors.</p></div></div>
<section class="studio-panel"><h2>No advertising provider connected</h2><p>Saving your real publisher ID adds the AdSense ownership meta tag and an ads.txt seller entry. It does not enable advertisements or guarantee approval.</p>
@can('manage-settings')<form method="post" action="{{ route('admin.advertisements.save') }}">@csrf<label class="admin-field">AdSense publisher ID<input name="adsense_publisher_id" placeholder="pub-0000000000000000" pattern="pub-[0-9]{16}" value="{{ old('adsense_publisher_id',$publisher) }}"><small>Use your own ID from AdSense. Leave blank until your account is ready.</small></label><button class="a-button primary">Save publisher ID</button></form>@endcan
<div class="operations-links"><a href="{{ route('ads-txt') }}">Inspect ads.txt</a><a href="https://support.google.com/adsense/answer/7584263" target="_blank" rel="noopener">Google's site connection guide</a></div></section>
<section class="studio-panel"><h2>Before enabling ads</h2><ul><li>Resolve the dependency and deployment launch blockers.</li><li>Publish original, reviewed articles with clear navigation and author information.</li><li>Complete AdSense site review and applicable privacy/consent requirements.</li><li>Reserve ad dimensions and measure mobile speed before adding ad scripts.</li></ul><p>Ad delivery remains disabled. No publisher identifier is invented.</p></section>
@endsection
