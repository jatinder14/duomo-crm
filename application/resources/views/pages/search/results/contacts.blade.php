<!--each category-->
<div class="x-each-category {{ $contacts['search_type'] ?? 'all'}}">

    <!--heading-->
    @if($contacts['search_type'] == 'all')
    <div class="x-heading clearfix">
        <span class="pull-left x-title">
            @lang('lang.contacts')
        </span>
        <span class="pull-right x-count">
            <a href="javascript:void(0);" class="ajax-request" data-url="{{ url('search?search_type=contacts') }}"
                data-type="form" data-form-id="global-search-form" data-ajax-type="post"
                data-loading-target="global-search-form" name="search_query">@lang('lang.view_all')
                ({{ $contacts['count'] }})</a>
        </span>
    </div>
    @endif

    <!--results-->
    <ul>

        <!-- each result -->
        @foreach($contacts['results']->take(runtimeSearchDisplyLimit($contacts['search_type'])) as $contact)
        <li class="contacts">
            <!--icon-->
            <span class="x-icon">
                <i class="sl-icon-people"></i>
            </span>
            <!--title-->
            <span class="x-title">
                <a href="{{ url('clients/'.$contact->client_id.'/contacts') }}">{{ $contact->first_name }}
                    {{ $contact->last_name }}</a>
            </span>
            <!--meta-->
            <span class="x-meta">
                - {{ $contact->client_company_name ?? '---'}}
            </span>
        </li>
        @endforeach

        <!--ajax loading-->

    </ul>
</div>