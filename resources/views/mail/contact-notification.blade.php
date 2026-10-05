<h1>New reader message</h1>
<p><strong>{{ $contact->subject }}</strong></p>
<p>From {{ $contact->name }} ({{ $contact->email }})</p>
<p style="white-space:pre-wrap">{{ $contact->message }}</p>
@if ($contact->article_url)
    <p>Related article: {{ $contact->article_url }}</p>
@endif
<p>Reference: {{ $contact->reference }}</p>
<p><a href="{{ rtrim(config('app.url'), '/') }}/admin/contacts">Open the private inbox</a></p>
