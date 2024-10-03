<!--each category-->
<div class="x-each-category {{ $contracts['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($contracts['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.contracts')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=contracts') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $contracts['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($contracts['results']->take(runtimeSearchDisplyLimit($contracts['search_type'])) as $contract)
        <li class="contracts">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="ti-write"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('contracts/'.$contract->doc_id) }}">{{ $contract->doc_title }}</a>
                </span>
                <!--matched  on tags-->
                @if($contract->tags->isNotEmpty() && $contract->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - #{{ runtimeContractIdFormat($contract->doc_id) }} -
                    {{ str_limit($contract->project_title ?? '---', 50) }}
                </span>
            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>