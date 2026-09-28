"""One-time conversion of approved static screens. Source package is never edited.

Do not rerun after editing generated Blade views without reviewing the diff.
"""
from pathlib import Path
import re
import shutil
import html

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / 'resources/views/FinancersHub-frontend/dist'
VIEWS = ROOT / 'resources/views'
pages = sorted(SOURCE.rglob('*.html'))
routes = {}
for page in pages:
    key = page.relative_to(SOURCE).as_posix()
    stem = key.removesuffix('.html')
    name = 'home' if stem == 'index' else stem.replace('/', '.')
    if stem == 'admin/index':
        name = 'admin.dashboard'
    url = '/' if stem == 'index' else '/admin' if stem == 'admin/index' else '/' + stem
    routes['/' + key] = (name, url, stem)
routes['/'] = routes['/index.html']


def convert(value):
    def link(match):
        attribute, url = match.groups()
        if url.startswith('/assets/'):
            return f'{attribute}="{{{{ asset(\'{url[1:]}\') }}}}"'
        path, sep, fragment = url.partition('#')
        if path in routes:
            return f'{attribute}="{{{{ route(\'{routes[path][0]}\') }}}}{sep}{fragment}"'
        return match.group(0)
    value = re.sub(r'(href|src|action)="([^"]+)"', link, value)
    value = re.sub(r'(<form\b[^>]*data-demo-form[^>]*>)(.*?)(</form>)',
                   lambda m: m[1] + re.sub(r'<(?:button|input)\b', lambda n: n[0] + ' disabled', m[2]) + m[3], value, flags=re.S)
    return value


def write(path, content):
    dest = VIEWS / path
    dest.parent.mkdir(parents=True, exist_ok=True)
    dest.write_text(content, encoding='utf-8')


home = (SOURCE / 'index.html').read_text(encoding='utf-8')
header = home.split('<body id="top">', 1)[1].split('<main', 1)[0]
footer = '<footer' + home.split('<footer', 1)[1].split('</body>', 1)[0]
write('partials/header.blade.php', convert(header))
write('partials/footer.blade.php', convert(footer))
studio = (SOURCE / 'admin/index.html').read_text(encoding='utf-8')
sidebar = '<aside' + studio.split('<aside', 1)[1].split('</aside>', 1)[0] + '</aside>'
sidebar = re.sub(r'class="side-link[^"]*" href="(/admin/[^"]+)"(?: aria-current=page)?',
                 lambda m: 'class="side-link {{ request()->routeIs(\'' + routes[m[1]][0] + '\') ? \'active\' : \'\' }}" href="' + m[1] + '" {{ request()->routeIs(\'' + routes[m[1]][0] + '\') ? \'aria-current=page\' : \'\' }}', sidebar)
write('partials/sidebar.blade.php', convert(sidebar))
topbar = '<header' + studio.split('<header', 1)[1].split('</header>', 1)[0] + '</header>'
topbar = topbar.replace('Overview</div>', "@yield('title')</div>")
write('partials/topbar.blade.php', convert(topbar))
head = '''<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="@yield('description', 'Independent finance guides and practical explanations for better money decisions.')">
<meta name="robots" content="noindex,nofollow"><meta name="color-scheme" content="light dark">
<title>@yield('title') | FinancersHub</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="stylesheet" href="{{ asset('assets/site.css') }}">
<link rel="stylesheet" href="{{ asset('assets/editorial.css') }}">
<link rel="stylesheet" href="{{ asset('assets/info.css') }}">
<link rel="stylesheet" href="{{ asset('assets/integration.css') }}">
<script src="{{ asset('assets/site.js') }}" defer></script>
'''
write('layouts/site.blade.php', head + '''</head><body id="top">
@include('partials.header')
<div class="design-notice" role="note">Design preview · Sample editorial content, authors and dates. Not a live publication.</div>
<main id="main">@yield('content')</main>
@include('partials.footer')
</body></html>
''')
write('layouts/studio.blade.php', head + '''<link rel="stylesheet" href="{{ asset('assets/admin.css') }}">
<script src="{{ asset('assets/studio.js') }}" defer></script>
</head><body id="top"><a class="skip" href="#main">Skip to content</a><div class="studio">
@include('partials.sidebar')
<div class="studio-workspace">@include('partials.topbar')
<main id="main" class="studio-main">
<div class="admin-sample"><span class="sample-dot"></span>Design preview · Sample content and figures · Saving and publishing are not connected.</div>
@yield('content')
</main></div></div></body></html>
''')
for page in pages:
    key = page.relative_to(SOURCE).as_posix()
    source = page.read_text(encoding='utf-8')
    title = html.unescape(re.search(r'<title>(.*?) \| FinancersHub</title>', source)[1])
    body = re.search(r'<main\b[^>]*>(.*?)</main>', source, re.S)[1]
    admin = key.startswith('admin/')
    if admin:
        body = re.sub(r'<div class="admin-sample">.*?</div>', '', body, count=1, flags=re.S)
        body = re.sub(r'<button\b(?![^>]*disabled)([^>]*data-admin-action[^>]*)>', r'<button disabled title="Not connected in design preview"\1>', body)
        body = re.sub(r'<button\b(?![^>]*disabled)([^>]*type="submit"[^>]*)>', r'<button disabled title="Not connected in design preview"\1>', body)
        body = body.replace('Save to this browser', 'Save draft (unavailable)').replace('Browser-local draft preview · no CMS connected', 'Design preview · no CMS connected').replace('This browser only', 'Not connected')
        body = re.sub(r'<div class="tinymce-setup".*?</div><label class="sr-only" for="article-body">', '<div class="tinymce-setup" id="tinymce-setup"><strong>Article editor</strong><p id="tinymce-status" role="status">Configure TINYMCE_API_KEY in the environment to enable the visual editor. The HTML textarea remains available.</p></div><label class="sr-only" for="article-body">', body, flags=re.S)
        body = body.replace('data-tinymce-key=""', 'data-tinymce-key="{{ config(\'financershub.tinymce_api_key\') }}"')
        body = re.sub(r'<button\b(?![^>]*(?:disabled|data-status-filter))([^>]*)>', r'<button disabled title="Not connected in design preview"\1>', body)
        body = body.replace('Saving keeps it in this browser; changes will appear on the public page after a CMS is connected and published.', 'Saving is unavailable until the CMS is connected.')
        body = body.replace('Save FAQ draft', 'Save FAQ draft (unavailable)')
        body = body.replace('Save draft in this browser', 'Save draft (unavailable)').replace('Save it in this browser for review;', 'Saving is unavailable;')
        body = re.sub(r'<form([^>]*)>', r'<form\1><fieldset disabled class="preview-fields">', body).replace('</form>', '</fieldset></form>')
    body = convert(body)
    title = title.replace("'", "\\'")
    layout = 'studio' if admin else 'site'
    write('design/' + key.removesuffix('.html') + '.blade.php', f"@extends('layouts.{layout}')\n@section('title', '{title}')\n@section('content')\n{body}\n@endsection\n")
for error in ['404', '500']:
    write(f'errors/{error}.blade.php', (VIEWS / f'design/{error}.blade.php').read_text(encoding='utf-8'))
lines = ['<?php', '', 'use Illuminate\\Support\\Facades\\Route;', '']
for page in pages:
    key = '/' + page.relative_to(SOURCE).as_posix()
    name, url, stem = routes[key]
    lines.append(f"Route::view('{url}', 'design.{stem.replace('/', '.')}')->name('{name}');")
    lines.append(f"Route::redirect('{key}', '{url}', 301);")
lines += ['']
(ROOT / 'routes/web.php').write_text('\n'.join(lines), encoding='utf-8')
shutil.copytree(SOURCE / 'assets', ROOT / 'public/assets', dirs_exist_ok=True)
print(f'Converted {len(pages)} screens into Blade with shared layouts and named routes.')
