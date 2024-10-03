<!--table-->
@if (@count($invoices ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--bill_invoiceid-->
                <th class="col_bill_invoiceid"><a href="javascript:void(0)">@lang('lang.id')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--client-->
                <th class="col_client"><a href="javascript:void(0)">@lang('lang.client')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_date-->
                <th class="col_bill_date"><a href="javascript:void(0)">@lang('lang.date')<span
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
                <!--payments-->
                <th class="col_payments"><a href="javascript:void(0)">@lang('lang.payments')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--amount_due-->
                <th class="col_amount_due"><a href="javascript:void(0)">@lang('lang.due')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_status-->
                <th class="col_bill_status"><a href="javascript:void(0)">@lang('lang.status')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.invoices.overview.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.invoices.overview.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif