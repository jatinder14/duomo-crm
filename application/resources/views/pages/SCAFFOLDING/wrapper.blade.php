@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--Page Title & Bread Crumbs -->


        <!-- action buttons -->
        @include('pages.fooos.components.misc.list-page-actions')
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!--stats panel-->
    @if(auth()->user()->is_team)
    <div id="stats-wrapper fooos-stats-wrapper">
    @include('misc.list-pages-stats')
    </div>
    @endif
    <!--stats panel-->


    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <!--fooos table-->
            @include('pages.fooos.components.table.wrapper')
            <!--fooos table-->
        </div>
    </div>
    <!--page content -->

    <!--filter-->
    @include('pages.fooos.components.misc.filter')
    <!--filter-->
</div>
<!--main content -->
@endsection