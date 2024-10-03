<!--modified Laravel Pagination Template: https://laravel.com/docs/8.x/pagination-->
@if ($paginator->hasPages())
<nav>
    <ul class="pagination">

        <!--previous link-->
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('previous')">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link ajax-request" href="javascript:void(0);"
                data-type="form" 
                data-ajax-type="POST"
                data-form-id="reports-list-page-filter-form"
                data-url="{{ $paginator->previousPageUrl() }}"
                rel="prev"
                aria-label="@lang('previous')">&lsaquo;</a>
        </li>
        @endif



        <!--each page buttons-->
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item">
            <a class="page-link ajax-request" 
            href="javascript:void(0);"
            data-ajax-type="POST"
            data-type="form" 
            data-form-id="reports-list-page-filter-form"
            data-url="{{ $url }}" >{{ $page }}
            </a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        <!--next link-->
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link ajax-request" 
            href="javascript:void(0);"
            data-type="form" 
            data-ajax-type="POST"
            data-form-id="reports-list-page-filter-form"
            data-url="{{ $paginator->nextPageUrl() }}" 
            rel="next"
            aria-label="@lang('next')">&rsaquo;</a>
        </li>
        @else
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('next')">
            <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>
        @endif
    </ul>
</nav>
@endif