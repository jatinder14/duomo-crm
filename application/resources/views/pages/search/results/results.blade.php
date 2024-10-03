<!--categories menu-->
@include('pages.search.modal.categories')


<!--search results-->
<div class="search-results-container" id="search-results-container">

    <!--no resulsts-->
    @if($count == 0)
    @include('pages.search.modal.404')
    @else

    <!--clients-->
    @if($clients['state'] && $clients['count'] > 0)
    @include('pages.search.results.clients')
    @endif

    <!--projects-->
    @if($projects['state'] && $projects['count'] > 0)
    @include('pages.search.results.projects')
    @endif

    <!--contacts-->
    @if($contacts['state'] && $contacts['count'] > 0)
    @include('pages.search.results.contacts')
    @endif

    <!--contracts-->
    @if($contracts['state'] && $contracts['count'] > 0)
    @include('pages.search.results.contracts')
    @endif

    <!--tasks-->
    @if($tasks['state'] && $tasks['count'] > 0)
    @include('pages.search.results.tasks')
    @endif

    <!--leads-->
    @if($leads['state'] && $leads['count'] > 0)
    @include('pages.search.results.leads')
    @endif

    <!--files-->
    @if($files['state'] && $files['count'] > 0)
    @include('pages.search.results.files')
    @endif

    <!--attachments-->
    @if($attachments['state'] && $attachments['count'] > 0)
    @include('pages.search.results.attachments')
    @endif

    <!--tickets-->
    @if($tickets['state'] && $tickets['count'] > 0)
    @include('pages.search.results.tickets')
    @endif

    <!--proposals-->
    @if($proposals['state'] && $proposals['count'] > 0)
    @include('pages.search.results.proposals')
    @endif

    <!--end-->
    @endif

</div>

<!--load more-->
@if(isset($search_type) && $search_type != 'all' && $count > 0)
@include('pages.search.modal.load-more')
<div class="p-b-50"></div>
@endif