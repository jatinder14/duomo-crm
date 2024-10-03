<!-- action buttons -->
@include('pages.templates.contracts.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="contracts-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($contracts ?? []) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--contracts table-->
<div class="card-embed-fix">
@include('pages.templates.contracts.components.table.wrapper')
</div>
<!--contracts table-->