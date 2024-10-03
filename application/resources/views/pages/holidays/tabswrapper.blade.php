<!-- action buttons -->
@include('pages.holidays.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if (auth()->user()->is_team)
    <div id="holidays-stats-wrapper" class="stats-wrapper card-embed-fix">
        @if (@count($holidays ?? []) > 0)
            @include('misc.list-pages-stats')
        @endif
    </div>
@endif
<!--stats panel-->

<!--holidays table-->
<div class="card-embed-fix">
    @include('pages.holidays.components.table.wrapper')
</div>
<!--holidays table-->
