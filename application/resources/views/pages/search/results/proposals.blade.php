<!--each category-->
<div class="x-each-category {{ $proposals['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($proposals['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.proposals')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=proposals') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $proposals['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($proposals['results']->take(runtimeSearchDisplyLimit($proposals['search_type'])) as $proposal)
        <li class="proposals">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="ti-bookmark-alt"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('proposals/'.$proposal->doc_id) }}">{{ $proposal->doc_title }}</a>
                </span>
                <!--matched  on tags-->
                @if($proposal->tags->isNotEmpty() && $proposal->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - #{{ runtimeProposalIdFormat($proposal->doc_id) }}
                </span>
            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>