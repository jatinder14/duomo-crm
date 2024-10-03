@if(auth()->user()->is_team)
@include('nav.leftmenu-team')
@endif

@if(auth()->user()->is_client)
@include('nav.leftmenu-client')
@endif

<!--[AFFILIATE]-->
@if(config('settings.custom_modules.cs_affiliate') && auth()->user()->type == 'cs_affiliate')
@include('pages.cs_affiliates.home.widgets.leftmenu')
@endif
