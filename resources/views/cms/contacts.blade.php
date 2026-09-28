@extends('layouts.studio')
@section('live','1')
@section('title','Contact messages')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">READER CONTACT</span><h1>Contact messages</h1><p>Private submissions stored for editorial review. Do not share reader details publicly.</p></div></div>
<nav class="inbox-filters" aria-label="Message filters">@foreach(['all'=>'All messages','unread'=>'Unread ('.$unread.')','pending'=>'Awaiting review','reviewed'=>'Reviewed','delivery-failed'=>'Delivery failed'] as $key=>$label)<a class="a-button {{ $filter === $key ? 'primary' : 'secondary' }}" href="{{ route('admin.contacts',array_merge(request()->only('reference','subject'),['filter'=>$key])) }}" @if($filter===$key) aria-current="page" @endif>{{ $label }}</a>@endforeach</nav>
<form method="get" class="studio-panel admin-toolbar"><input type="hidden" name="filter" value="{{ $filter }}"><label class="admin-field">Message reference<input name="reference" maxlength="36" value="{{ request('reference') }}" placeholder="Paste the full reference"></label><label class="admin-field">Topic<select name="subject"><option value="">All topics</option>@foreach(['Question about an article','Correction or update','Topic suggestion','General inquiry'] as $subject)<option @selected(request('subject')===$subject)>{{ $subject }}</option>@endforeach</select></label><div class="toolbar-actions"><button class="a-button primary">Find messages</button><a class="a-button secondary" href="{{ route('admin.contacts') }}">Reset</a></div></form>
<p class="admin-results" role="status">{{ $messages->total() }} matching {{ Str::plural('message',$messages->total()) }}</p>
@forelse($messages as $message)
<section class="studio-panel"><span class="admin-kicker">{{ $message->resolved_at ? 'REVIEWED' : ($message->read_at ? 'READ' : 'UNREAD') }}</span><h2>{{ $message->subject }}</h2><p>{{ $message->name }} · {{ $message->email }}</p><p style="white-space:pre-wrap">{{ $message->message }}</p>
@if($message->article_url)
<p>Related URL: {{ $message->article_url }}</p>
@endif
<p class="meta">Email notification: {{ ucfirst(str_replace('_',' ',$message->notification_status)) }} @if($message->notified_at) &middot; Accepted by mail transport {{ $message->notified_at->diffForHumans() }}@endif</p>
<div class="inbox-actions"><form method="post" action="{{ route('admin.contacts.read',$message) }}">@csrf<input type="hidden" name="unread" value="{{ $message->read_at ? 1 : 0 }}"><button class="a-button secondary">{{ $message->read_at ? 'Mark unread' : 'Mark read' }}</button></form>
@if(in_array($message->notification_status,['failed','not_configured']))<form method="post" action="{{ route('admin.contacts.retry',$message) }}">@csrf<button class="a-button secondary" @disabled(!$deliveryReady) @if(!$deliveryReady) title="Configure a recipient and mail transport first" @endif>Queue notification</button></form>@endif</div>
<small>Received {{ $message->created_at }} · {{ $message->reference }}</small>
@if(!$message->resolved_at)
<form method="post" action="{{ route('admin.contacts.resolve',$message) }}">
@csrf
<button class="a-button secondary">Mark reviewed</button></form>
@endif
@if($message->resolved_at)<form method="post" action="{{ route('admin.contacts.reopen',$message) }}">@csrf<button class="a-button secondary">Reopen for review</button></form>@endif
</section>
@empty
<section class="studio-panel"><p>No messages match these filters. Try another topic or clear the reference.</p></section>
@endforelse
{{ $messages->links('pagination.simple') }}
@endsection
