@extends('layouts.studio')
@section('live', '1')
@section('title', 'Newsletter')
@section('content')
    <div class="studio-heading">
        <div><span class="admin-kicker">PUBLICATION</span>
            <h1>Newsletter</h1>
            <p>Consent-aware subscription infrastructure is prepared. Delivery is not connected.</p>
        </div>
    </div>
    <div class="admin-metrics three">
        <div><span>Confirmed subscribers</span><strong>{{ $confirmed }}</strong><small>Database records</small></div>
        <div><span>Delivery provider</span><strong>—</strong><small>Not configured</small></div>
        <div><span>Signup status</span><strong>Off</strong><small>No addresses are collected</small></div>
    </div>
    <section class="studio-panel">
        <h2>Signup remains disabled</h2>
        <p>Newsletter signup is deferred by the publication owner. Reader accounts will be added in a later phase. When
            newsletter work resumes, a provider and verified confirmation and unsubscribe flows will be required before
            collecting addresses.</p><a class="a-button secondary" href="{{ route('admin.settings') }}">Edit newsletter
            invitation</a>
    </section>
@endsection
