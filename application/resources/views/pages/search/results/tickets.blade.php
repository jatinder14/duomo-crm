<!--each category-->
<div class="x-each-category {{ $tickets['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($tickets['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.tickets')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=tickets') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $tickets['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($tickets['results']->take(runtimeSearchDisplyLimit($tickets['search_type'])) as $ticket)
        <li class="tickets">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="ti-comments"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('tickets/'.$ticket->ticket_id) }}">{{ $ticket->ticket_subject }}</a>
                </span>
                <!--matched  on tags-->
                @if($ticket->tags->isNotEmpty() && $ticket->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - #{{ $ticket->ticket_id }} - {{ str_limit($ticket->client_company_name ?? '', 50) }}
                </span>
            </a>
        </li>
        @endforeach

    </ul>
</div>