<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-timesheets">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i
                    class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_timesheets')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-timesheets"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body p-l-35 p-r-35">

                <!--standard fields-->
                <div class="">
                    <h5>@lang('lang.standard_fields')</h5>
                </div>
                <div class="line"></div>
                <div class="row">

                    <!--timesheet_user-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_user]"
                                    name="standard_field[timesheet_user]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[timesheet_user]">@lang('lang.user')</label>
                            </div>
                        </div>
                    </div>


                    <!--timesheet_client-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_client]"
                                    name="standard_field[timesheet_client]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[timesheet_client]">@lang('lang.client')</label>
                            </div>
                        </div>
                    </div>


                    <!--timesheet_client_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_client_id]"
                                    name="standard_field[timesheet_client_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[timesheet_client_id]">@lang('lang.client_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--timesheet_task-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_task]"
                                    name="standard_field[timesheet_task]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[timesheet_task]">@lang('lang.task')</label>
                            </div>
                        </div>
                    </div>

                    <!--timesheet_project-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_project]"
                                    name="standard_field[timesheet_project]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[timesheet_project]">@lang('lang.project')</label>
                            </div>
                        </div>
                    </div>

                    <!--timesheet_project_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_project_id]"
                                    name="standard_field[timesheet_project_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[timesheet_project_id]">@lang('lang.project_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--timesheet_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_date]"
                                    name="standard_field[timesheet_date]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[timesheet_date]">@lang('lang.date')</label>
                            </div>
                        </div>
                    </div>

                    <!--timesheet_time-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[timesheet_time]"
                                    name="standard_field[timesheet_time]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[timesheet_time]">@lang('lang.time')</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button" id="export-timesheets-button"
                        data-url="{{ urlResource('/export/timesheets?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->