@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('pages.settings.sections.foos.misc.list-page-actions')
<!-- action buttons -->

<!--heading-->
@include('pages.settings.sections.foos.table.table')

<!--section js resource-->
@endsection