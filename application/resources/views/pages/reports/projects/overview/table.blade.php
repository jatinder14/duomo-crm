<!--table-->
@if (@count($projects ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--project_id-->
                <th class="col_project_id"><a href="javascript:void(0)">@lang('lang.id')<span class="sorting-icons"><i
                                class="ti-arrows-vertical"></i></span></a></th>

                <!--project_title-->
                <th class="col_project_title"><a href="javascript:void(0)">@lang('lang.project')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--project_date_due-->
                <th class="col_due_date"><a href="javascript:void(0)">@lang('lang.due_date')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_due-->
                <th class="col_count_tasks_due"><a href="javascript:void(0)">@lang('lang.due_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_completed-->
                <th class="col_count_tasks_completed"><a href="javascript:void(0)">@lang('lang.completed_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                <!--sum_hours-->
                <th class="col_sum_hours"><a href="javascript:void(0)">@lang('lang.hours')<span class="sorting-icons"><i
                                class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_expenses-->
                <th class="col_sum_expenses"><a href="javascript:void(0)">@lang('lang.expenses')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_invoices-->
                <th class="col_sum_invoices"><a href="javascript:void(0)">@lang('lang.invoices')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_payments-->
                <th class="col_sum_payments"><a href="javascript:void(0)">@lang('lang.payments')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--project_status-->
                <th class="col_project_status"><a href="javascript:void(0)">@lang('lang.status')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.projects.overview.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.projects.overview.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif