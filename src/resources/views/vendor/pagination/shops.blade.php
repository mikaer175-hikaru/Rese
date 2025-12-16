@if ($paginator->hasPages())
    <nav class="c-pagination" role="navigation" aria-label="ページネーション">
        <span class="c-pagination__list">

            {{-- 前へ --}}
            @if ($paginator->onFirstPage())
                <span class="c-pagination__link c-pagination__link--disabled" aria-disabled="true">
                    <svg class="c-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            @else
                <a class="c-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <svg class="c-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @endif

            {{-- ページ番号 --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="c-pagination__link c-pagination__link--disabled">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <span class="c-pagination__link c-pagination__link--active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a class="c-pagination__link" href="{{ $url }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- 次へ --}}
            @if ($paginator->hasMorePages())
                <a class="c-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <svg class="c-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @else
                <span class="c-pagination__link c-pagination__link--disabled" aria-disabled="true">
                    <svg class="c-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            @endif

        </span>
    </nav>
@endif
