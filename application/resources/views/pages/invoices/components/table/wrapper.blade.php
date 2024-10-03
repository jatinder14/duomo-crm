<!--bulk actions-->
@include('pages.invoices.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.invoices.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.invoices.components.misc.filter-invoices')
@endif
<!--filter-->

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.invoices.export')
@endif