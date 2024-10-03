<!--bulk actions-->
@include('pages.holidays.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.holidays.components.table.table')

<!--filter-->
@if (auth()->user()->is_team)
    @include('pages.holidays.components.misc.filter-holidays')
@endif
<!--holidays-->

<!--export-->
@if (config('visibility.list_page_actions_exporting'))
    @include('pages.export.holidays.export')
@endif
