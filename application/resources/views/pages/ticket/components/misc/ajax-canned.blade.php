@if(count($canned_messages) > 0)
@foreach($canned_messages as $canned)
@include('pages.ticket.components.misc.canned')
@endforeach
@else
<div class="page-notification">
    <img src="{{ url('/') }}/public/images/no-results-found.png" alt="404" />
    <div class="title">@lang('lang.no_results_found')</div>
</div>
@endif