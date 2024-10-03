<!--each category-->
<div class="x-each-category {{ $clients['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($clients['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.clients')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=clients') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $clients['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($clients['results']->take(runtimeSearchDisplyLimit($clients['search_type'])) as $client)
        <li class="clients">
            <a href="javascript:void(0);">
                <!--icon-->
                <span class="x-icon">
                    <i class="sl-icon-user"></i>
                </span>
                <!--title-->
                <span class="x-title">
                    <a href="{{ url('clients/'.$client->client_id) }}">{{ $client->client_company_name }}</a>
                </span>
                <!--matched  on tags-->
                @if($client->tags->isNotEmpty() && $client->tags->contains('tag_title', $search_query))
                <span class="ti-bookmark x-tag-match" title="@lang('lang.matched_tags')" data-toggle="tooltip"></span>
                @endif
                <!--meta-->
                <span class="x-meta">
                    - #{{ $client->client_id ?? 0 }}
                </span>
            </a>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>