@extends('layouts.studio')
@section('live','1')
@php($label = $kind === 'faq' ? 'FAQ' : \Illuminate\Support\Str::singular(ucfirst($kind)))
@section('title',($editing ? 'Edit ' : 'Create ').$label)
@section('content')
<a class="admin-back" href="{{ route('admin.'.$kind) }}">&larr; Back to {{ $kind === 'faq' ? 'FAQs' : $kind }}</a>
<div class="studio-heading"><div><span class="admin-kicker">CONTENT MANAGEMENT</span><h1>@yield('title')</h1><p>{{ $editing ? 'Update the details below. Existing article relationships are preserved.' : 'Add a new entry to your publication.' }}</p></div></div>
<div class="crud-form-layout"><section class="studio-panel"><form method="post" action="{{ $editing ? route('admin.content.update',[$kind,$editing->id]) : route('admin.content.store',$kind) }}">
@csrf
@if($editing)
@method('put')
@endif
@if($kind==='faq')
<label class="admin-field">Topic<select name="group">
@foreach(['using-financershub'=>'Using FinancersHub','newsletter-and-contact'=>'Newsletter and contact','privacy-and-editorial-standards'=>'Privacy and editorial standards'] as $key=>$label)
<option value="{{ $key }}" @selected(old('group',$editing?->group)===$key)>{{ $label }}</option>
@endforeach
</select></label>
<label class="admin-field">Question<input name="question" required value="{{ old('question',$editing?->question) }}"></label>
<label class="admin-field">Answer<textarea name="answer" rows="6" required>{{ old('answer',$editing?->answer) }}</textarea></label>
<label class="admin-field">Order<input type="number" name="position" min="0" value="{{ old('position',$editing?->position ?? 0) }}" required></label>
<label class="check-line"><input type="hidden" name="published" value="0"><input type="checkbox" name="published" value="1" @checked(old('published',$editing?->published))><span>Published</span></label>
@else
<label class="admin-field">Name<input name="name" required value="{{ old('name',$editing?->name) }}"></label>
<label class="admin-field">URL slug<input name="slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" value="{{ old('slug',$editing?->slug) }}"></label>
@if($kind==='authors')
<label class="admin-field">Biography<textarea name="bio" rows="6">{{ old('bio',$editing?->bio) }}</textarea></label>
<label class="admin-field">Linked staff account<select name="user_id"><option value="">No linked account</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('user_id',$editing?->user_id)==$user->id)>{{ $user->name }} ({{ ucfirst($user->role) }})</option>@endforeach</select><small>Link a staff account to let that writer manage their own drafts.</small></label>
@else
<label class="admin-field">Description<textarea name="description" rows="6">{{ old('description',$editing?->description) }}</textarea></label>
@endif
@endif
<div class="form-actions"><button class="a-button primary">Save {{ $kind==='faq' ? 'FAQ' : 'content' }}</button>
<a class="a-button secondary" href="{{ route('admin.'.$kind) }}">Back to list</a></div>
</form></section><aside class="studio-panel crud-help"><span class="admin-kicker">EDITORIAL NOTES</span><h2>A useful entry starts with clarity.</h2><p>{{ $kind === 'faq' ? 'Keep answers concise. Save as a draft until the wording has been reviewed. Order controls the position within each topic.' : 'Use a descriptive name and a short, stable URL slug. An entry referenced by articles cannot be deleted until those references are reassigned.' }}</p>@if($editing)<dl><dt>Created</dt><dd>{{ $editing->created_at?->format('M j, Y') }}</dd><dt>Last updated</dt><dd>{{ $editing->updated_at?->format('M j, Y H:i') }} UTC</dd></dl>@endif</aside></div>
@endsection
