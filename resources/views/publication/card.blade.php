<article class="card">
    @if ($item->mediaAsset)<a href="{{ \App\Support\Localization::route('articles.show', $item->slug) }}">
            <div class="art teal"><img src="{{ \App\Support\Localization::route('media.show', $item->media_asset_id) }}"
                    srcset="{{ $item->mediaAsset->srcset }}" sizes="(max-width: 640px) calc(100vw - 32px), (max-width: 1000px) 45vw, 400px"
                    alt="{{ $item->publicTranslation()?->image_alt ?: $item->mediaAsset->alt_text }}" loading="lazy" width="{{ $item->mediaAsset->width ?? 800 }}"
                    height="{{ $item->mediaAsset->height ?? 500 }}"></div>
        </a>
    @endif
    <div class="body"><a class="eyebrow"
            href="{{ \App\Support\Localization::route('categories.show', $item->category->slug) }}">{{ __($item->category->name ?? '') }} </a>
        <h3><a href="{{ \App\Support\Localization::route('articles.show', $item->slug) }}">{{ $item->title }} </a></h3>
        <p>{{ $item->excerpt }} </p><span class="meta">{{ $item->authorProfile->name }} ·
            {{ $item->published_at?->format('M j, Y') }} &middot; {{ $item->reading_minutes }} {{ __('min read') }} </span>
    </div>
</article>
