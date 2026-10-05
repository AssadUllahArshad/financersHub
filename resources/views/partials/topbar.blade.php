<header class="studio-topbar"><button class="mobile-studio-menu" id="studio-menu" type="button"
        aria-label="Open studio menu" aria-expanded="false"><x-icon name="menu" /></button>
    <div class="studio-crumb"><span class="crumb-context">FinancersHub / Editorial studio / </span><span
            class="crumb-current">@yield('title')</span></div>
    <div class="topbar-actions"><button class="studio-theme" id="studio-theme" type="button"
            aria-label="Switch to dark mode" title="Switch to dark mode"><x-icon name="moon" class="theme-moon" /><x-icon name="sun" class="theme-sun" /></button><a href="{{ route('home') }}"
            target="_blank" rel="noopener">View site ↗</a><a class="topbar-create" href="{{ route('admin.editor') }}">＋
            New article</a>
        <form method="post" action="{{ route('logout') }}">@csrf<button type="submit" class="studio-signout"
                aria-label="Sign out"><x-icon name="logout" /> Sign out</button></form><a
            href="{{ route('admin.profile') }}" class="topbar-avatar"
            aria-label="My profile">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</a>
    </div>
</header>
