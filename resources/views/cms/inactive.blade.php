@extends('layouts.studio')
@section('live','1')
@section('title',$service)
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">PUBLICATION SERVICES</span><h1>{{ $service }}</h1><p>This service is currently inactive.</p></div></div>
<section class="studio-panel"><h2>{{ $service === 'Comments' ? 'Public comments are disabled' : 'No advertising provider connected' }}</h2><p>{{ $service === 'Comments' ? 'Readers cannot submit comments. Moderation, spam protection and reporting must be configured before comments are enabled.' : 'No advertisements are served and no advertising tracking is installed. Provider selection and placement review are required before activation.' }}</p><a class="a-button secondary" href="{{ route('admin.dashboard') }}">Back to overview</a></section>
@endsection
