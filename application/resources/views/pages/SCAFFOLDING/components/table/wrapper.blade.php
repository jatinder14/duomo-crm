<!--main table view-->
@include('pages.fooos.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.fooos.components.misc.filter')
@endif
<!--filter-->