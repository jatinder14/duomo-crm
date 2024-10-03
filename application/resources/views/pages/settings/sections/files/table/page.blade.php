@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('pages.settings.sections.files.misc.list-page-actions')
<!-- action buttons -->

<!--heading-->
@include('pages.settings.sections.files.table.table')

<!--section js resource-->
@endsection