<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-attendances">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i
                    class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_attendances')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-attendances"></i>
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
                    <!--attendance_team_member-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_team_member]"
                                    name="standard_field[attendance_team_member]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[attendance_team_member]">@lang('lang.team_member')</label>
                            </div>
                        </div>
                    </div>

                    <!--attendance_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_date]"
                                    name="standard_field[attendance_date]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[attendance_date]">@lang('lang.date')</label>
                            </div>
                        </div>
                    </div>

                    <!--attendance_in_time-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_in_time]"
                                    name="standard_field[attendance_in_time]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[attendance_in_time]">@lang('lang.in_time')</label>
                            </div>
                        </div>
                    </div>

                    <!--attendance_out_time-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_out_time]"
                                    name="standard_field[attendance_out_time]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[attendance_out_time]">@lang('lang.out_time')</label>
                            </div>
                        </div>
                    </div>

                    <!--attendance_reason-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_reason]"
                                    name="standard_field[attendance_reason]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[attendance_reason]">@lang('lang.reason')</label>
                            </div>
                        </div>
                    </div>

                    <!--attendance_status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[attendance_status]"
                                    name="standard_field[attendance_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[attendance_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        id="export-attendances-button" data-url="{{ urlResource('/export/attendances?') }}"
                        data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->
