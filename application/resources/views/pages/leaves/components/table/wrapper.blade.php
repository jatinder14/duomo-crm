<!--bulk actions-->
@include('pages.leaves.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.leaves.components.table.table')

<!--filter-->
@if (auth()->user()->is_team)
    @include('pages.leaves.components.misc.filter-leaves')
@endif
<!--leaves-->

<!--export-->
@if (config('visibility.list_page_actions_exporting'))
    @include('pages.export.leaves.export')
@endif
