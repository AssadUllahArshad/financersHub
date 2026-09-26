@extends('layouts.studio')
@section('section','index')
@section('title','Overview')
@section('intro','Keep the publication moving, from first draft to final review.')
@section('content')
<div class="admin-metrics">
  @foreach([['Published articles',$metrics['published'] ?? 0],['In review',$metrics['review'] ?? 0],['Scheduled',$metrics['scheduled'] ?? 0],['Drafts',$metrics['drafts'] ?? 0]] as [$label,$number])
    <div><span>{{ $label }}</span><strong>{{ $number }}</strong><small>Editorial workflow</small></div>
  @endforeach
</div>
<div class="admin-two-col dashboard-activity">
  <x-studio-panel title="Reader activity" eyebrow="ANALYTICS"><div class="chart" role="img" aria-label="Reader activity for the last seven days">@foreach(($readerActivity ?? []) as $day)<div class="chart-col"><div class="chart-bar" style="height:{{ max(0,min(100,(int)($day['percentage'] ?? 0))) }}%"></div><span>{{ $day['label'] }}</span></div>@endforeach</div>@if(empty($readerActivity))<p class="chart-note">Connect analytics to show reader activity.</p>@endif</x-studio-panel>
  <x-studio-panel title="Recent activity" eyebrow="EDITORIAL WORKFLOW">@forelse(($recentActivity ?? []) as $event)<div class="activity-item"><span class="activity-icon" aria-hidden="true">✎</span><div><strong>{{ $event['title'] }}</strong><span>{{ $event['detail'] }}</span></div><time>{{ $event['when'] }}</time></div>@empty<p class="chart-note">Editorial updates will appear here.</p>@endforelse</x-studio-panel>
</div>
<div class="admin-two-col attention-panel">
  <x-studio-panel title="Needs attention" eyebrow="YOUR WORKFLOW"><div class="table-scroll"><table class="studio-table"><thead><tr><th>Article</th><th>Section</th><th>Status</th><th>Updated</th></tr></thead><tbody>
    @forelse($articles as $article)
      <tr><td><a class="story-title" href="{{ url('/admin/articles/'.$article['id'].'/edit') }}">{{ $article['title'] }}</a></td><td>{{ $article['category'] }}</td><td><x-status-badge :tone="$article['status_tone'] ?? 'neutral'">{{ $article['status'] }}</x-status-badge></td><td>{{ $article['updated_label'] }}</td></tr>
    @empty<tr><td colspan="4">No articles require attention.</td></tr>@endforelse
  </tbody></table></div></x-studio-panel>
  <x-studio-panel title="Editorial calendar" eyebrow="COMING UP"><p class="small-muted" style="padding:0 25px">Scheduling data will appear here when publishing is connected.</p></x-studio-panel>
</div>
@endsection
