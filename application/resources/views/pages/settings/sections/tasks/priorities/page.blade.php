@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('pages.settings.sections.tasks.misc.list-page-actions')
<!-- action buttons -->

<!--heading-->
@include('pages.settings.sections.tasks.priorities.table')


@if(config('system.settings_type') == 'standalone')
<!--[standalone] - settings documentation help-->
<div>
    <a href="https://growcrm.io/documentation" target="_blank" class="btn btn-sm btn-info help-documentation"><i
            class="ti-info-alt"></i>
        {{ cleanLang(__('lang.help_documentation')) }}</a>
</div>
@endif

@endsection