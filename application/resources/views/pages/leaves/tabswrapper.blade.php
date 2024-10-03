<!-- action buttons -->
@include('pages.leaves.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if (auth()->user()->is_team)
    <div id="leaves-stats-wrapper" class="stats-wrapper card-embed-fix">
        @if (@count($leaves ?? []) > 0)
            @include('misc.list-pages-stats')
        @endif
    </div>
@endif
<!--stats panel-->

<!--leaves table-->
<div class="card-embed-fix">
    @include('pages.leaves.components.table.wrapper')
</div>
<!--leaves table-->
