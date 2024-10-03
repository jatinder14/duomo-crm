<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-holidays">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_holidays')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-holidays"></i>
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

                    <!--holiday_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[holiday_date]"
                                    name="standard_field[holiday_date]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[holiday_date]">@lang('lang.date')</label>
                            </div>
                        </div>
                    </div>

                    <!--holiday_description-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[holiday_description]"
                                    name="standard_field[holiday_description]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[holiday_description]">@lang('lang.description')</label>
                            </div>
                        </div>
                    </div>

                    <!--buttons-->
                    <div class="buttons-block">
                        <button type="button"
                            class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                            id="export-holidays-button" data-url="{{ urlResource('/export/holidays?') }}"
                            data-type="form" data-ajax-type="POST"
                            data-button-loading-annimation="yes">@lang('lang.export')</button>
                    </div>
                </div>
    </form>
</div>
<!--sidebar-->
