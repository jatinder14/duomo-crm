<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-projects">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_projects')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-projects"></i>
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

                    <!--project_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_id]" name="standard_field[project_id]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[project_id]">@lang('lang.id')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_created-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_created]"
                                    name="standard_field[project_created]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_created]">@lang('lang.date_created')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_title-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_title]"
                                    name="standard_field[project_title]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[project_title]">@lang('lang.title')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_status]"
                                    name="standard_field[project_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[project_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_clientid-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_clientid]"
                                    name="standard_field[project_clientid]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_clientid]">@lang('lang.client_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_client_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_client_name]"
                                    name="standard_field[project_client_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_client_name]">@lang('lang.client_name')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_created_by-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_created_by]"
                                    name="standard_field[project_created_by]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_created_by]">@lang('lang.created_by')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_category_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_category_name]"
                                    name="standard_field[project_category_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_category_name]">@lang('lang.category')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_date_start-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_date_start]"
                                    name="standard_field[project_date_start]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_date_start]">@lang('lang.start_date')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_date_due-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_date_due]"
                                    name="standard_field[project_date_due]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_date_due]">@lang('lang.due_date')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_description-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_description]"
                                    name="standard_field[project_description]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_description]">@lang('lang.description')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_progress-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_progress]"
                                    name="standard_field[project_progress]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_progress]">@lang('lang.progress')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_billing_type-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_billing_type]"
                                    name="standard_field[project_billing_type]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_billing_type]">@lang('lang.billing_type')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_billing_estimated_hours-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_billing_estimated_hours]"
                                    name="standard_field[project_billing_estimated_hours]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_billing_estimated_hours]">@lang('lang.estimated_hours')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_billing_costs_estimate-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_billing_costs_estimate]"
                                    name="standard_field[project_billing_costs_estimate]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_billing_costs_estimate]">@lang('lang.estimated_cost')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_visibility-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_visibility]"
                                    name="standard_field[project_visibility]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_visibility]">@lang('lang.visibility')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_tasks_all-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_tasks_all]"
                                    name="standard_field[project_tasks_all]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_tasks_all]">@lang('lang.all_tasks')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_tasks_due-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_tasks_due]"
                                    name="standard_field[project_tasks_due]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_tasks_due]">@lang('lang.due_tasks')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_tasks_completed-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_tasks_completed]"
                                    name="standard_field[project_tasks_completed]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_tasks_completed]">@lang('lang.completed_tasks')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_invoices_all-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_invoices_all]"
                                    name="standard_field[project_sum_invoices_all]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_invoices_all]">@lang('lang.all_invoices')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_invoices_due-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_invoices_due]"
                                    name="standard_field[project_sum_invoices_due]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_invoices_due]">@lang('lang.due_invoices')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_invoices_overdue-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_invoices_overdue]"
                                    name="standard_field[project_sum_invoices_overdue]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_invoices_overdue]">@lang('lang.overdue_invoices')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_invoices_paid-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_invoices_paid]"
                                    name="standard_field[project_sum_invoices_paid]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_invoices_paid]">@lang('lang.paid_invoices')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_payments-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_payments]"
                                    name="standard_field[project_sum_payments]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_payments]">@lang('lang.payments')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_sum_expenses-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_sum_expenses]"
                                    name="standard_field[project_sum_expenses]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_sum_expenses]">@lang('lang.expenses')</label>
                            </div>
                        </div>
                    </div>

                </div>

                <!--custon fields-->
                <div class="m-t-30">
                    <h5>@lang('lang.custom_fields')</h5>
                </div>
                <div class="line"></div>
                <div class="row">
                    @foreach($fields as $field)
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="custom_field[{{ $field->customfields_name }}]"
                                    name="custom_field[{{ $field->customfields_name }}]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="custom_field[{{ $field->customfields_name }}]">{{ $field->customfields_title }}</label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button" id="export-projects-button"
                        data-url="{{ urlResource('/export/projects?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->