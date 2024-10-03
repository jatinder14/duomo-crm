<!--checkbox actions-->
@include('pages.projects.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.projects.views.list.table.table')
<!--filter-->
@if(auth()->user()->is_team)
@include('pages.projects.components.misc.filter-projects')
@endif

<!--custom table view-->
@include('pages.projects.components.misc.table-config')


<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.projects.export')
@endif