<!--bulk actions-->
@include('pages.attendances.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.attendances.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.attendances.components.misc.filter-attendances')
@endif
<!--attendances-->

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.attendances.export')
@endif