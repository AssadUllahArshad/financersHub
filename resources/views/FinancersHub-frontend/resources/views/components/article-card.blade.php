@props(['article'])
<article class="card">
  <a href="{{ url('/articles/'.$article['slug']) }}" aria-label="Read {{ $article['title'] }}"><div class="art {{ $article['visual'] ?? 'blue' }}"><img src="{{ asset($article['image'] ?? 'assets/images/editorial-investing.webp') }}" alt="" width="800" height="500" loading="lazy"></div></a>
  <div class="body"><a class="eyebrow" href="{{ url('/categories/'.$article['category_slug']) }}">{{ $article['category'] }}</a><h3><a href="{{ url('/articles/'.$article['slug']) }}">{{ $article['title'] }}</a></h3><p>{{ $article['excerpt'] }}</p><span class="meta">{{ $article['read_time'] ?? 'Guide' }}</span></div>
</article>
