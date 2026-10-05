@if ($paginator->hasPages())<nav class="pagination-shell" aria-label="{{ __('Pagination') }}">
        <ul class="pagination">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())<span class="page-link" aria-disabled="true">{{ __('Previous') }}</span>@else<a class="page-link" rel="prev"
                        href="{{ $paginator->previousPageUrl() }}">{{ __('Previous') }}</a>
                @endif
            </li>
            <li class="page-item pagination-position"><span>{{ __('Page :current of :total', ['current' => $paginator->currentPage(), 'total' => $paginator->lastPage()]) }}</span></li>
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                @if ($paginator->hasMorePages())<a class="page-link" rel="next" href="{{ $paginator->nextPageUrl() }}">{{ __('Next') }}</a>@else<span
                        class="page-link" aria-disabled="true">{{ __('Next') }}</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
