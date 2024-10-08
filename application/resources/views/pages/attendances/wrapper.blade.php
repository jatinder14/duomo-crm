@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">

        <!--page heading-->
        <div class="row page-titles">

            <!-- Page Title & Bread Crumbs -->
            @include('misc.heading-crumbs')
            <!--Page Title & Bread Crumbs -->


            <!-- action buttons -->
            @include('pages.attendances.components.misc.list-page-actions')
            <!-- action buttons -->

        </div>
        <!--page heading-->

        <!-- page content -->
        <div class="row">
            <div class="col-12">
                <!--attendances table-->
                @include('pages.attendances.components.table.wrapper')
                <!--attendances table-->
            </div>
        </div>
        <!--page content -->

    </div>
    <!--main content -->
@endsection
