<!-- action buttons -->
@include('pages.canned.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="canned-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($canned ?? []) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--canned table-->
<div class="card-embed-fix">
@include('pages.canned.components.table.table')
</div>
<!--canned table-->