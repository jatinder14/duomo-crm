<!--table-->
@if (@count($timesheets ?? []) > 0)
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--timesheet_team_member-->
                <th class="col_project_id"><a href="javascript:void(0)">@lang('lang.team_member')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                <!--timesheet_role-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.role')<span
                            class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                <!--sum_not_invoiced-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.not_invoiced')<span class="sorting-icons"><i
                        class="ti-arrows-vertical"></i></span></a></th>

                        
                <!--sum_invoiced-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.invoiced')<span class="sorting-icons"><i
                        class="ti-arrows-vertical"></i></span></a></th>

                <!--sum_hours-->
                <th class="col_count_projects_completed"><a href="javascript:void(0)">@lang('lang.total') <span
                            class="align-middle text-info font-16" data-toggle="tooltip"
                            title="@lang('lang.hours') : @lang('lang.minutes')" data-placement="top"><i
                                class="ti-info-alt"></i></span><span class="sorting-icons"><i
                                class="ti-arrows-vertical"></i></span></a></th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            <!--rows-->
            @include('pages.reports.timesheets.team.ajax')
        </tbody>
        <tfoot>
            <!--rows-->
            @include('pages.reports.timesheets.team.footer')
        </tfoot>
    </table>

</div>
@else
<div id="report-results-container">
    @include('notifications.no-results-found')
</div>
@endif