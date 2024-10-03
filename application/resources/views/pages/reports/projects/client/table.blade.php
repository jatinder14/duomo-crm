<!--table-->
@if (@count($projects ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--client_company_name-->
                <th class="col_project_id"><a href="javascript:void(0)">@lang('lang.client')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_projects-->
                <th class="col_count_projects"><a href="javascript:void(0)">@lang('lang.all_projects')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--count_projects_not_started-->
                <th class="col_count_projects_pending"><a href="javascript:void(0)">@lang('lang.not_started')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--count_projects_on_hold-->
                <th class="col_count_projects"><a href="javascript:void(0)">@lang('lang.on_hold')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--count_projects_cancelled-->
                <th class="col_count_projects"><a href="javascript:void(0)">@lang('lang.cancelled')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                <!--count_projects_completed-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.completed')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_due-->
                <th class="col_count_tasks_due"><a href="javascript:void(0)">@lang('lang.due_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--count_tasks_completed-->
                <th class="col_count_tasks_completed"><a href="javascript:void(0)">@lang('lang.completed_tasks')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                <!--sum_hours-->
                <th class="col_sum_hours"><a href="javascript:void(0)">@lang('lang.hours_mins')<span class="sorting-icons"><i
                                class="ti-arrows-vertical"></i></span></a></th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.projects.client.ajax')
        </tbody>
        <tfoot>
            @include('pages.reports.projects.client.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif