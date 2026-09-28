@extends('layouts.studio')
@section('live','1')
@section('title','Media library')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">EDITORIAL STUDIO</span><h1>Media library</h1><p>Store images with meaningful alternative text and rights information. New uploads are resized to a maximum 1600px edge and converted to WebP when supported.</p></div></div>
<section class="studio-panel"><form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
@csrf
<label class="admin-field">Image (JPEG, PNG or WebP; up to 5 MB)<input type="file" name="image" accept="image/jpeg,image/png,image/webp" required></label>
<label class="admin-field">Alternative text<input name="alt_text" required value="{{ old('alt_text') }}"></label>
<label class="admin-field">Rights and provenance<textarea name="rights" required>{{ old('rights') }}</textarea></label><button class="a-button primary">Upload image</button></form></section>
<form method="get" class="studio-panel admin-toolbar"><label class="admin-field">Search images<input type="search" name="q" value="{{ request('q') }}" placeholder="Filename or alternative text"></label><label class="admin-field">Format<select name="type"><option value="">All formats</option><option value="image/webp" @selected(request('type')==='image/webp')>WebP</option><option value="image/png" @selected(request('type')==='image/png')>PNG</option></select></label><div class="toolbar-actions"><button class="a-button primary">Apply filters</button><a href="{{ route('admin.media') }}">Reset</a></div></form><p>{{ $media->total() }} images found</p><div class="media-grid">
@forelse($media as $item)
<section class="studio-panel"><img src="{{ route('media.show',$item) }}" alt="{{ $item->alt_text }}" width="300" height="180" style="object-fit:cover;max-width:100%"><h2>{{ $item->original_name }}</h2><form method="post" action="{{ route('admin.media.update',$item) }}">
@csrf
@method('put')
<label class="admin-field">Alternative text<input name="alt_text" value="{{ $item->alt_text }}" required></label><label class="admin-field">Rights<textarea name="rights" required>{{ $item->rights }}</textarea></label><button class="a-button secondary">Save metadata</button></form><form method="post" action="{{ route('admin.media.destroy',$item) }}" data-confirm="Delete this image permanently? Images referenced by articles are protected.">
@csrf
@method('delete')
<button class="a-button danger">Delete image</button></form></section>
@empty
<p>No uploaded images yet.</p>
@endforelse
</div>{{ $media->links('pagination.simple') }}
@endsection
