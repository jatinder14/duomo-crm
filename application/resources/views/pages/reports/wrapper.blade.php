@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!-- Page Title & Bread Crumbs -->
    <div class="row page-titles">

        <div class="col-md-12 col-lg-6 align-self-center">
            <h3 class="text-themecolor">{{ $page['heading'] ?? '' }}
            </h3>
            <!--crumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
                <li class="breadcrumb-item reports-breadcrumbs active">@lang('lang.reports')</li>
                <li class="breadcrumb-item reports-breadcrumbs hidden" id="reports-breadcrumbs-heading"><!--dynamic--></li>
                <li class="breadcrumb-item reports-breadcrumbs hidden" id="reports-breadcrumbs-sub-heading"><!--dynamic--></li>
            </ol>
            <!--crumbs-->
        </div>

        <!--filter panel-->
        <div class="col-md-12  col-lg-6 align-self-center text-right reports-list-page-actions-container"
            id="reports-list-page-actions-container">
            <div id="list-page-actions">
                <!--dynamic-->

            </div>
        </div>
    </div>

    <!--topnav-->
    @include('pages.reports.components.misc.topnav')
    <!--topnav-->

    <!-- page content -->
    <div class="row m-t-10" id="reports-tab-single-screen">
        <!--dynamic ajax section-->
        <div class="col-lg-12">
            <div class="card min-h-400">
                <div class="tab-content">
                    <div class="tab-pane active ext-ajax-container" id="reportss_ajaxtab" role="tabpanel">
                        <div class="card-body tab-body tab-body-embedded" id="embed-content-container">
                            <!--dynamic content here-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--page content -->

</div>
<!--page content -->
</div>
<!--main content -->

<!--ajax tab initial loading - summary-->
<span id="dynamic-reports-content" class="js-ajax-ux-request hidden" data-loading-target="embed-content-container"
    data-url="{{ $page['dynamic_url'] ?? '' }}">
</span>
@endsection