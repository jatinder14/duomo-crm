<!--checkbox actions-->
@include('pages.projects.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.projects.views.cards.layout.cards')
<!--filter-->
@if(auth()->user()->is_team)
@include('pages.projects.components.misc.filter-projects')
@endif
<!--filter-->

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.projects.export')
@endif
<!--export-->