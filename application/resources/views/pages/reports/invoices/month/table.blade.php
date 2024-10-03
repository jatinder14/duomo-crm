<!--table-->
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--month-->
                <th class="col_month"><a href="javascript:void(0)">@lang('lang.month')<span
                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--bill_amount_before_tax-->
                <th class="col_count"><a href="javascript:void(0)">@lang('lang.count')<span
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
            @include('pages.reports.invoices.month.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.invoices.month.footer')
        </tfoot>
    </table>

</div>