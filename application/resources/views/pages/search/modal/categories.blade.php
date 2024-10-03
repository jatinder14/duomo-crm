@if($count > 0)
<div class="search-categories" id="search-categories">

    <!--all-->
    <span
        class="x-each-category {{ runtimeSearchCurrentMenu('all', $current_category) }} ajax-request search-category-button"
        data-url="{{ url('search?search_type=all') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.all') ({{ $count ?? 0 }})
    </span>

    <!--clients-->
    @if(config('search.clients'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('clients', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=clients') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.clients') ({{ $clients['count'] ?? 0 }})
    </span>
    @endif

    </span>

    <!--contacts-->
    @if(config('search.contacts'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('contacts', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=contacts') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.contacts') ({{ $contacts['count'] ?? 0 }})
    </span>
    @endif

    <!--projects-->
    @if(config('search.projects'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('projects', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=projects') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.projects') ({{ $projects['count'] ?? 0 }})
    </span>
    @endif

    <!--tasks-->
    @if(config('search.tasks'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('tasks', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=tasks') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.tasks') ({{ $tasks['count'] ?? 0 }})
    </span>
    @endif

    <!--leads-->
    @if(config('search.leads'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('leads', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=leads') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.leads') ({{ $leads['count'] ?? 0 }})
    </span>
    @endif

    <!--files-->
    @if(config('search.files'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('files', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=files') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.files') ({{ $files['count'] ?? 0 }})
    </span>
    @endif

    <!--attachments-->
    @if(config('search.attachments'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('attachments', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=attachments') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.attachments') ({{ $attachments['count'] ?? 0 }})
    </span>
    @endif

    <!--tickets-->
    @if(config('search.tickets'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('tickets', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=tickets') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.tickets') ({{ $tickets['count'] ?? 0 }})
    </span>
    @endif

    <!--contracts-->
    @if(config('search.contracts'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('contracts', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=contracts') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.contracts') ({{ $contracts['count'] ?? 0 }})
    </span>
    @endif

    <!--proposals-->
    @if(config('search.proposals'))
    <span
        class="x-each-category  {{ runtimeSearchCurrentMenu('proposals', $current_category) }}  search-category-button ajax-request"
        data-url="{{ url('search?search_type=proposals') }}" data-type="form" data-form-id="global-search-form"
        data-ajax-type="post" data-loading-target="global-search-form">
        @lang('lang.proposals') ({{ $proposals['count'] ?? 0 }})
    </span>
    @endif

</div>
@endif