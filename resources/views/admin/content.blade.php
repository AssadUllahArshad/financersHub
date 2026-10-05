@extends('layouts.studio')
@section('live', '1')
@section('title', $kind === 'faq' ? 'FAQs' : ucfirst($kind))
@section('content')
    <div class="studio-heading">
        <div><span class="admin-kicker">CONTENT MANAGEMENT</span>
            <h1>@yield('title')</h1>
            <p>{{ $total }} entries in your publication. Organize, review and maintain your content.</p>
        </div><a class="a-button primary" href="{{ route('admin.content.create', $kind) }}">+ Add
            {{ $kind === 'faq' ? 'FAQ' : \Illuminate\Support\Str::singular($kind) }}</a>
    </div>
    <form method="get" class="studio-panel admin-toolbar"><label class="admin-field">Search<input class="form-control"
                type="search" name="q" value="{{ request('q') }}"
                placeholder="Search {{ $kind === 'faq' ? 'questions' : $kind }}"></label>
        @if ($kind === 'faq')
            <label class="admin-field">Visibility<select class="form-select" name="status">
                    <option value="">All entries</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                </select></label>
        @endif
        <label class="admin-field">Sort by<select class="form-select" name="sort">
                <option value="">{{ $kind === 'faq' ? 'Editorial order' : 'Name A to Z' }}</option>
                <option value="newest" @selected(request('sort') === 'newest')>Newest first</option>
            </select></label>
        <div class="toolbar-actions"><button class="a-button primary">Apply filters</button><a class="a-button secondary"
                href="{{ route('admin.' . $kind) }}">Reset</a></div>
    </form>
    <section class="studio-panel record-list">
        <div class="record-list-heading">
            <h2>All {{ $kind === 'faq' ? 'FAQs' : $kind }}</h2><span class="meta">{{ $records->total() }} matching
                entries</span>
        </div>
        <div class="studio-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ $kind === 'faq' ? 'Question' : 'Name' }}</th>
                        <th>{{ $kind === 'faq' ? 'Visibility' : 'Articles' }}</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td><a class="record-title"
                                    href="{{ route('admin.content.edit', [$kind, $record->id]) }}">{{ $record->question ?? $record->name }}</a><small
                                    class="record-caption">{{ $kind === 'faq' ? 'Order ' . $record->position . ' / ' . ucfirst(str_replace('-', ' ', $record->group)) : '/' . $record->slug }}</small>
                            </td>
                            <td>
                                @if ($kind === 'faq')
                                    <span
                                        class="status-pill {{ $record->published ? 'success' : 'neutral' }}">{{ $record->published ? 'Published' : 'Draft' }}</span>
                                @else
                                    {{ $record->articles_count }}
                                @endif
                            </td>
                            <td>{{ $record->updated_at->format('M j, Y') }}</td>
                            <td>
                                <div class="row-actions"><a class="a-button secondary compact"
                                        href="{{ route('admin.content.edit', [$kind, $record->id]) }}">Edit</a>
                                    <form method="post" action="{{ route('admin.content.destroy', [$kind, $record->id]) }}"
                                        data-confirm="Delete this entry? This cannot be undone. Referenced entries cannot be deleted.">
                                        @csrf @method('delete')<button class="a-button danger compact"
                                            @disabled($kind !== 'faq' && $record->articles_count > 0)
                                            @if ($kind !== 'faq' && $record->articles_count > 0) title="Reassign article references before deleting" @endif>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty<tr>
                            <td colspan="4">
                                <div class="admin-empty">
                                    <h3>No matching entries</h3>
                                    <p>Try a different search or add your first entry.</p><a class="a-button secondary"
                                        href="{{ route('admin.content.create', $kind) }}">Create an entry</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{ $records->links('pagination.simple') }}
    </section>
@endsection
