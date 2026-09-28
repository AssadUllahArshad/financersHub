<aside class="studio-sidebar" id="studio-sidebar">
<a class="studio-brand" href="{{ route('home') }}">
<img src="{{ asset('assets/logo-white.svg') }}" alt="FinancersHub" width="184" height="34">
<span>EDITORIAL STUDIO</span>
</a>
<nav aria-label="Editorial studio">
<div class="side-group">
<div class="side-group-label">Workspace</div>
<a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" {{ request()->routeIs('admin.dashboard') ? 'aria-current=page' : '' }}>
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<rect x="3" y="3" width="7" height="7" rx="1"/>
<rect x="14" y="3" width="7" height="7" rx="1"/>
<rect x="3" y="14" width="7" height="7" rx="1"/>
<rect x="14" y="14" width="7" height="7" rx="1"/>
</svg>
</span>
<span>Overview</span>
</a>
<a class="side-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}" href="{{ route('admin.articles') }}" {{ request()->routeIs('admin.articles') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M6 3h9l4 4v14H6z"/>
<path d="M15 3v5h4M9 12h7M9 16h7"/>
</svg>
</span>
<span>Articles</span>
</a>
<a class="side-link {{ request()->routeIs('admin.editor', 'admin.articles.edit') ? 'active' : '' }}" href="{{ route('admin.editor') }}" {{ request()->routeIs('admin.editor', 'admin.articles.edit') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M4 20h16M6 16l10-10 3 3-10 10-4 1zM15 7l2-2a2 2 0 0 1 3 3l-2 2"/>
</svg>
</span>
<span>Write article</span>
</a>
@can('manage-content')<a class="side-link {{ request()->routeIs('admin.media') ? 'active' : '' }}" href="{{ route('admin.media') }}" {{ request()->routeIs('admin.media') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<rect x="3" y="4" width="18" height="16" rx="2"/>
<circle cx="8" cy="9" r="1.5"/>
<path d="m4 17 5-5 3 3 3-3 5 5"/>
</svg>
</span>
<span>Media library</span>
</a>@endcan
</div>
<div class="side-group">
<div class="side-group-label">Editorial</div>
@can('manage-content')<a class="side-link {{ (request()->routeIs('admin.categories') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'categories')) ? 'active' : '' }}" href="{{ route('admin.categories') }}" {{ (request()->routeIs('admin.categories') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'categories')) ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M4 6h7l2 2h7v11H4zM4 6V4h7"/>
</svg>
</span>
<span>Categories</span>
</a>@endcan

@can('manage-content')<a class="side-link {{ (request()->routeIs('admin.tags') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'tags')) ? 'active' : '' }}" href="{{ route('admin.tags') }}" {{ (request()->routeIs('admin.tags') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'tags')) ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M3 4h10l8 8-9 9-9-9z"/>
<circle cx="8" cy="9" r="1"/>
</svg>
</span>
<span>Tags</span>
</a>@endcan

@can('manage-content')<a class="side-link {{ (request()->routeIs('admin.authors') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'authors')) ? 'active' : '' }}" href="{{ route('admin.authors') }}" {{ (request()->routeIs('admin.authors') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'authors')) ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<circle cx="9" cy="8" r="3"/>
<path d="M3 20v-2a6 6 0 0 1 12 0v2zM17 6a3 3 0 0 1 0 6M18 15a5 5 0 0 1 3 5"/>
</svg>
</span>
<span>Authors</span>
</a>@endcan

@can('manage-content')<a class="side-link {{ (request()->routeIs('admin.faq') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'faq')) ? 'active' : '' }}" href="{{ route('admin.faq') }}" {{ (request()->routeIs('admin.faq') || (request()->routeIs('admin.content.*') && request()->route('kind') === 'faq')) ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M4 4h16v13H9l-5 4z"/>
<path d="M8 9h8M8 13h5"/>
</svg>
</span>
<span>FAQs</span>
</a>@endcan
<a class="side-link {{ request()->routeIs('admin.comments') ? 'active' : '' }}" href="{{ route('admin.comments') }}" {{ request()->routeIs('admin.comments') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M4 4h16v13H9l-5 4z"/>
<path d="M8 9h8M8 13h5"/>
</svg>
</span>
<span>Comments</span>
</a>
</div>
<div class="side-group">
<div class="side-group-label">Publication</div>
@can('manage-settings')<a class="side-link {{ request()->routeIs('admin.seo') ? 'active' : '' }}" href="{{ route('admin.seo') }}" {{ request()->routeIs('admin.seo') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<circle cx="10" cy="10" r="6"/>
<path d="m15 15 6 6M8 10h4"/>
</svg>
</span>
<span>SEO & discovery</span>
</a>@endcan
<a class="side-link {{ request()->routeIs('admin.advertisements') ? 'active' : '' }}" href="{{ route('admin.advertisements') }}" {{ request()->routeIs('admin.advertisements') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<rect x="3" y="5" width="18" height="14" rx="2"/>
<path d="M6 9h12M6 13h8"/>
</svg>
</span>
<span>Advertising</span>
</a>
@can('manage-settings')<a class="side-link {{ request()->routeIs('admin.newsletter') ? 'active' : '' }}" href="{{ route('admin.newsletter') }}" {{ request()->routeIs('admin.newsletter') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<rect x="3" y="5" width="18" height="14" rx="2"/>
<path d="m4 7 8 6 8-6"/>
</svg>
</span>
<span>Newsletter</span>
</a>@endcan

@can('manage-settings')<a class="side-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}" {{ request()->routeIs('admin.settings') ? 'aria-current=page' : '' }} >
<span class="side-icon">
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<circle cx="12" cy="12" r="3"/>
<path d="M10 2h4l1 3 3 1 3-1 2 4-2 2v3l2 2-2 4-3-1-3 1-1 3h-4l-1-3-3-1-3 1-2-4 2-2v-3L1 9l2-4 3 1 3-1z"/>
</svg>
</span>
<span>Settings</span>
</a>@endcan
</div>
@can('manage-settings')<a class="side-link {{ request()->routeIs('admin.contacts') ? 'active' : '' }}" href="{{ route('admin.contacts') }}">Contact messages ({{ \App\Models\ContactMessage::whereNull('read_at')->count() }})</a>@endcan
@can('manage-settings')
<a class="side-link {{ request()->routeIs('admin.maintenance') ? 'active' : '' }}" href="{{ route('admin.maintenance') }}">Maintenance console</a>
@endcan
</nav>
<div class="side-bottom">
<div class="user-dot" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name,0,1)) }}</div>
<div>
<strong>{{ auth()->user()->name }}</strong>
<small>{{ ucfirst(auth()->user()->role) }}</small>
</div>
<span aria-hidden="true">⋯</span>
</div>
</aside>