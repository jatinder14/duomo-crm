@extends('layout.wrapperplain') @section('content')
<!-- main content -->
<div class="container-fluid {{ $page['mode'] ?? '' }}" id="invoice-container">

    <!--BILL CONTENT-->
    <div class="row">
        <div class="col-md-12 p-t-30">
            @include('pages.bill.bill-web')
        </div>
    </div>
</div>
<!--main content -->

@endsection