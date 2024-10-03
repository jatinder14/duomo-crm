<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-leaves">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_leaves')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-leaves"></i>
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
                    <!--leave_team_member-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_team_member]"
                                    name="standard_field[leave_team_member]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_team_member]">@lang('lang.team_member')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_date]" name="standard_field[leave_date]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[leave_date]">@lang('lang.date')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_category-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_category]"
                                    name="standard_field[leave_category]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_category]">@lang('lang.category')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_reason-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_reason]"
                                    name="standard_field[leave_reason]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_reason]">@lang('lang.reason')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_status]"
                                    name="standard_field[leave_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_comment-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_comment]"
                                    name="standard_field[leave_comment]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_comment]">@lang('lang.comment')</label>
                            </div>
                        </div>
                    </div>

                    <!--leave_created-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[leave_created]"
                                    name="standard_field[leave_created]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[leave_created]">@lang('lang.created')</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        id="export-leaves-button" data-url="{{ urlResource('/export/leaves?') }}" data-type="form"
                        data-ajax-type="POST" data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->
