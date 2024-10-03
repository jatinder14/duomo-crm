@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--Page Title & Bread Crumbs -->


        <!-- action buttons -->
        @include('pages.canned.components.misc.list-page-actions')
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <!--canned table-->
            @include('pages.canned.components.body')
            <!--canned table-->
        </div>
    </div>
    <!--page content -->

    <!--filter-->
    @include('pages.canned.components.misc.filter')
    <!--filter-->
</div>
<!--main content -->
@endsection