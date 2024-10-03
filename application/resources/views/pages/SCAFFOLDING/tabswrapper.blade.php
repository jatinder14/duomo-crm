<!-- action buttons -->
@include('pages.fooos.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="fooos-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($fooos ?? []) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--fooos table-->
<div class="card-embed-fix">
@include('pages.fooos.components.table.wrapper')
</div>
<!--fooos table-->