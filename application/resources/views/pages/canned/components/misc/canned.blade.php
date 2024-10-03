@if(count($canned_responses) > 0)

  @foreach($canned_responses as $canned)

     @include('pages.ticket.components.misc.canned')

  @endforeach

@else
<div class="page-notification">
    <img src="{{ url('/') }}/public/images/no-results-found.png" alt="404" />
    <h4>@lang('lang.canned_no_recently_found')</h4>
    <h5>@lang('lang.canned_you_can_search_or_browse')</h5>
</div>
@endif