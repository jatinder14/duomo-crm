<!--bulk actions-->
@include('pages.tickets.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.tickets.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.tickets.components.misc.filter-tickets')
@endif
<!--filter-->


<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.tickets.export')
@endif
<!--export-->