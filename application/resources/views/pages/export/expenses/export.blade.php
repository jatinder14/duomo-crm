<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-expenses">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_expenses')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-expenses"></i>
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

                    <!--expense_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expense_date]"
                                    name="standard_field[expense_date]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[expense_date]">@lang('lang.date')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_user-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_user]"
                                    name="standard_field[expenses_user]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[expenses_user]">@lang('lang.user')</label>
                            </div>
                        </div>
                    </div>

                    <!--expense_description-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expense_description]"
                                    name="standard_field[expense_description]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expense_description]">@lang('lang.description')</label>
                            </div>
                        </div>
                    </div>

                    <!--expense_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expense_amount]"
                                    name="standard_field[expense_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expense_amount]">@lang('lang.amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_client-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_client]"
                                    name="standard_field[expenses_client]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[expenses_client]">@lang('lang.client')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_client_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_client_id]"
                                    name="standard_field[expenses_client_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expenses_client_id]">@lang('lang.client_id')</label>
                            </div>
                        </div>
                    </div>


                    <!--expenses_project-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_project]"
                                    name="standard_field[expenses_project]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expenses_project]">@lang('lang.project')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_project_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_project_id]"
                                    name="standard_field[expenses_project_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expenses_project_id]">@lang('lang.project_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_invoiced-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_invoiced]"
                                    name="standard_field[expenses_invoiced]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expenses_invoiced]">@lang('lang.invoiced')</label>
                            </div>
                        </div>
                    </div>

                    <!--expenses_invoice_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[expenses_invoice_id]"
                                    name="standard_field[expenses_invoice_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[expenses_invoice_id]">@lang('lang.invoice_id')</label>
                            </div>
                        </div>
                    </div>

                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button" id="export-expenses-button"
                        data-url="{{ urlResource('/export/expenses?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->