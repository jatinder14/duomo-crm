<!--table-->
@if (@count($clients ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>
                <th></th>
                <th colspan="2" class="text-center reports-th-heading">@lang('lang.projects')</th>
                <th colspan="3" class="text-center reports-th-heading-contrast">@lang('lang.invoices')</th>
                <th colspan="2" class="text-center reports-th-heading">@lang('lang.estimates')</th>
                <th colspan="3" class="text-center reports-th-heading-contrast">@lang('lang.expenses')</th>
            </tr>
            <tr>

                <!--client_company_name-->
                <th class="col_project_id"><a href="javascript:void(0)">@lang('lang.client')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_projects_completed-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.completed')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--count_projects_pending-->
                <th class="col_count_projects_pending"><a href="javascript:void(0)">@lang('lang.pending')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--sum_invoices_paid-->
                <th class="col_sum_invoices_paid"><a href="javascript:void(0)">@lang('lang.paid')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--sum_invoices_due-->
                <th class="col_sum_invoices_due"><a href="javascript:void(0)">@lang('lang.due')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--sum_invoices_overdue-->
                <th class="col_sum_invoices_due"><a href="javascript:void(0)">@lang('lang.overdue')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--sum_estimates_accepted-->
                <th class="col_sum_estimates_accepted"><a href="javascript:void(0)">@lang('lang.accepted')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_estimates_declined-->
                <th class="col_sum_estimates_declined"><a href="javascript:void(0)">@lang('lang.declined')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_expenses_invoiced-->
                <th class="col_sum_expenses_invoiced"><a href="javascript:void(0)">@lang('lang.invoiced')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                <!--sum_expenses_not_invoiced-->
                <th class="col_sum_hours"><a href="javascript:void(0)">@lang('lang.not_invoiced')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_expenses_not_billable-->
                <th class="col_sum_hours"><a href="javascript:void(0)">@lang('lang.not_billable')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.clients.overview.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.clients.overview.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif