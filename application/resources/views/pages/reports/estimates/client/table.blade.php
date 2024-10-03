<!--table-->
@if (@count($estimates ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>
                <!--client-->
                <th class="col_client"><a href="javascript:void(0)">@lang('lang.client')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--estimate_count-->
                <th class="col_estimate_count"><a href="javascript:void(0)">@lang('lang.count')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_amount_before_tax-->
                <th class="col_bill_amount_before_tax"><a href="javascript:void(0)">@lang('lang.before_tax')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_tax_total_amount-->
                <th class="col_bill_tax_total_amount"><a href="javascript:void(0)">@lang('lang.tax')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_discount_amount-->
                <th class="col_bill_discount_amount"><a href="javascript:void(0)">@lang('lang.discounts')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_adjustment_amount-->
                <th class="col_bill_adjustment_amount"><a href="javascript:void(0)">@lang('lang.adjustments')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_final_amount-->
                <th class="col_bill_final_amount"><a href="javascript:void(0)">@lang('lang.total')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.estimates.client.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.estimates.client.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif