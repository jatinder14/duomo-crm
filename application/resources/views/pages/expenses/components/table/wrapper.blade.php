<!--bulk actions-->
@include('pages.expenses.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.expenses.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.expenses.components.misc.filter-expenses')
@endif
<!--filter-->

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.expenses.export')
@endif