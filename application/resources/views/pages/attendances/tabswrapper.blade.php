<!-- action buttons -->
@include('pages.attendances.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="attendances-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($attendances ?? []) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--attendances table-->
<div class="card-embed-fix">
@include('pages.attendances.components.table.wrapper')
</div>
<!--attendances table-->