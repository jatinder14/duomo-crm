<!--table-->
@if (@count($projects ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--project_title-->
                <th class="col_project_title"><a class="ajax-request" href="javascript:void(0)">@lang('lang.client')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_projects_pending-->
                <th class="col_count_projects_pending"><a class="ajax-request" href="javascript:void(0)">@lang('lang.completed')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_projects_completed-->
                <th class="col_count_projects_completed"><a class="ajax-request" href="javascript:void(0)">@lang('lang.pending')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_due-->
                <th class="col_count_tasks_due"><a class="ajax-request" href="javascript:void(0)">@lang('lang.due_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_completed-->
                <th class="col_count_tasks_completed"><a class="ajax-request" href="javascript:void(0)">@lang('lang.completed_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                <!--sum_hours-->
                <th class="col_sum_hours"><a class="ajax-request" href="javascript:void(0)">@lang('lang.hours')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_expenses-->
                <th class="col_sum_expenses"><a class="ajax-request" href="javascript:void(0)">@lang('lang.expenses')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_invoices-->
                <th class="col_sum_invoices"><a class="ajax-request" href="javascript:void(0)">@lang('lang.invoices')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_payments-->
                <th class="col_sum_payments"><a class="ajax-request" href="javascript:void(0)">@lang('lang.payments')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.projects.category.ajax')
        </tbody>
        <tfoot>
            @include('pages.reports.projects.category.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif