<!--each category-->
<div class="x-each-category {{ $leads['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($leads['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.leads')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=leads') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $leads['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($leads['results']->take(runtimeSearchDisplyLimit($leads['search_type'])) as $lead)
        <li class="leads">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="sl-icon-call-in"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('/leads/v/'.$lead->lead_id.'/'.$lead->lead_id) }}">{{ $lead->lead_title }}</a>
                </span>
                <!--matched  on tags-->
                @if($lead->tags->isNotEmpty() && $lead->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - {{ $lead->lead_firstname }} {{ $lead->lead_lastname }}
                </span>
            </a>
        </li>
        @endforeach
        <!--ajax loading-->

    </ul>
</div>