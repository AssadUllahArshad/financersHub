@extends('layouts.studio')
@section('live', '1')
@section('title', 'Dashboard')
@section('content')
    <div class="dashboard-page">
        <header class="dash-heading">
            <div><span class="admin-kicker">PUBLICATION OVERVIEW <span class="dash-dot"></span>
                    {{ now()->format('D, M j, Y') }}</span>
                <h1>Your editorial command center.</h1>
                <p>Welcome back, {{ auth()->user()->name }}. Here is where things stand today.</p>
            </div><a class="a-button primary" href="{{ route('admin.editor') }}">+ Create article</a>
        </header>
        <div class="dash-stats">
            @foreach (['published' => ['Published', 'Live editorial content'], 'draft' => ['Drafts', 'Ideas taking shape'], 'in-review' => ['In review', 'Ready for a closer look'], 'scheduled' => ['Scheduled', 'Upcoming publications']] as $status => $label)
                <a class="dash-stat dash-stat-{{ $status }}"
                    href="{{ route('admin.articles', ['status' => $status]) }}"><span>{{ $label[0] }} <span
                            aria-hidden="true">&nearr;</span></span><strong>{{ number_format($counts[$status] ?? 0) }}</strong><small>{{ $label[1] }}</small></a>
            @endforeach
        </div>
        <div class="dash-columns">
            <div class="dash-main">
                @if ($audience)
                    <section class="dash-card">
                        <div class="dash-card-head">
                            <div><span class="admin-kicker">AUDIENCE</span>
                                <h2>A week at a glance</h2>
                            </div><a href="{{ route('admin.analytics', ['days' => 7]) }}">View analytics &rarr;</a>
                        </div>
                        <div class="dash-audience">
                            <div><strong>{{ number_format($audience['views']) }}</strong><span>Page views</span></div>
                            <div><strong>{{ number_format($audience['visitors']) }}</strong><span>Approximate
                                    visitors</span></div><span class="dash-period">Last 7 days &middot; UTC</span>
                        </div>
                        <div class="dash-chart" role="img"
                            aria-label="Daily page views: {{ $audience['trend']->map(fn($day) => $day['date'] . ': ' . $day['total'])->implode(', ') }}">
                            @foreach ($audience['trend'] as $day)
                                <div class="dash-chart-column"><span>{{ $day['total'] }}</span>
                                    <div class="dash-chart-track">
                                        <div style="height:{{ ($day['total'] / $audience['peak']) * 100 }}%"></div>
                                    </div><small>{{ $day['label'] }}</small>
                                </div>
                            @endforeach
                        </div>
                        <p class="dash-note">
                            {{ $audience['views'] ? 'Counts use protected IP hashes. Shared networks and changing IPs affect visitor estimates.' : 'No eligible visits recorded this week. Staff visits and known bots are excluded.' }}
                        </p>
                </section>@else<section class="dash-card dash-welcome"><span class="admin-kicker">FOCUS FOR TODAY</span>
                        <h2>Turn a useful idea into a clear guide.</h2>
                        <p>Start a draft, check your sources, and send your work for editorial review.</p><a
                            class="a-button secondary" href="{{ route('admin.editor') }}">Start writing &rarr;</a>
                    </section>
                @endif
                <section class="dash-card">
                    <div class="dash-card-head">
                        <div><span class="admin-kicker">CONTENT WORKSPACE</span>
                            <h2>Recently updated articles</h2>
                        </div><a href="{{ route('admin.articles') }}">View all &rarr;</a>
                    </div>
                    <div class="studio-table-wrap">
                        <table class="admin-table dash-table">
                            <thead>
                                <tr>
                                    <th>Article / author</th>
                                    <th>Status</th>
                                    <th>Last updated</th>
                                    <th><span class="sr-only">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td><a class="dash-article-title"
                                                href="{{ route('admin.articles.edit', $article) }}">{{ $article->title }}</a><small>{{ $article->authorProfile?->name ?? 'Unassigned author' }}</small>
                                        </td>
                                        <td><span
                                                class="dash-badge dash-badge-{{ $article->status }}">{{ ucfirst(str_replace('-', ' ', $article->status)) }}</span>
                                        </td>
                                        <td><time
                                                datetime="{{ $article->updated_at->toIso8601String() }}">{{ $article->updated_at->format('M j, Y') }}</time>
                                        </td>
                                        <td><a href="{{ route('admin.articles.edit', $article) }}"
                                                aria-label="Edit {{ $article->title }}">Edit &rarr;</a></td>
                                </tr>@empty<tr>
                                        <td colspan="4">
                                            <div class="dash-empty"><span aria-hidden="true">&#9998;</span>
                                                <h3>Your next story starts here.</h3>
                                                <p>Create an article to begin building your publication.</p><a
                                                    class="a-button secondary" href="{{ route('admin.editor') }}">Write
                                                    your first draft</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <aside class="dash-side">
                <section class="dash-card"><span class="admin-kicker">PRIORITIES</span>
                    <h2>Needs attention</h2><a class="dash-task"
                        href="{{ route('admin.articles', ['status' => 'in-review']) }}"><span><strong>Editorial
                                review</strong><small>Articles awaiting a
                                decision</small></span><b>{{ $counts['in-review'] ?? 0 }}</b></a>
                    @if ($inbox)
                        <a class="dash-task" href="{{ route('admin.contacts', ['filter' => 'unread']) }}"><span><strong>Unread
                                    messages</strong><small>New reader
                                    conversations</small></span><b>{{ $inbox['unread'] }}</b></a><a class="dash-task"
                            href="{{ route('admin.contacts', ['filter' => 'pending']) }}"><span><strong>Open
                                    conversations</strong><small>Messages awaiting
                                    resolution</small></span><b>{{ $inbox['pending'] }}</b></a>
                    @endif
                    <a class="dash-task" href="{{ route('admin.articles', ['status' => 'draft']) }}">
                        <span><strong>Draft workspace</strong><small>Continue your work in
                                progress</small></span><b>{{ $counts['draft'] ?? 0 }}</b></a>
                </section>
                <section class="dash-card"><span class="admin-kicker">YOUR SHORTCUTS</span>
                    <h2>Quick actions</h2>
                    <nav class="dash-shortcuts" aria-label="Dashboard shortcuts"><a
                            href="{{ route('admin.editor') }}">Write an article <span>&rarr;</span></a>
                        @can('manage-content')
                            <a href="{{ route('admin.media') }}">Manage media <span>&rarr;</span></a><a
                                href="{{ route('admin.categories') }}">Organize categories <span>&rarr;</span></a>
                            @endcan @can('manage-settings')
                            <a href="{{ route('admin.seo') }}">Search appearance <span>&rarr;</span></a><a
                                href="{{ route('admin.maintenance') }}">Maintenance console <span>&rarr;</span></a>
                        @endcan
                        <a href="{{ route('home') }}">
                            Visit the website <span>&nearr;</span></a>
                    </nav>
                </section>
                <section class="dash-card dash-summary"><span class="admin-kicker">EDITORIAL INVENTORY</span>
                    <h2>{{ number_format($total) }} articles</h2>
                    <p>{{ auth()->user()->role === 'author' ? 'Your content only.' : 'Across the publication.' }} Demo
                        content and trashed articles are excluded.</p>
                    @if (($counts['unpublished'] ?? 0) > 0)
                        <a href="{{ route('admin.articles', ['status' => 'unpublished']) }}">{{ $counts['unpublished'] }}
                            unpublished &rarr;</a>
                    @endif
                </section>
            </aside>
        </div>
    </div>
@endsection
