    <!--project_automation_status-->
    <div class="form-group row">
        <label class="col-sm-3 text-left control-label col-form-label">@lang('lang.automation')</label>
        <div class="col-sm-9 text-left">
            <select class="select2-basic form-control form-control-sm select2-preselected"
                id="project_automation_status" name="project_automation_status"
                data-preselected="{{  $automation['project_automation_status'] }}">
                <option></option>
                <option value="enabled">@lang('lang.enabled')</option>
                <option value="disabled">@lang('lang.disabled')</option>
            </select>
        </div>
    </div>

    <!--automation options container-->
    <div class="client-selector m-t-20  {{ projectAutomationVisibility($automation['project_automation_status']) }}"
        id="automation-options-container">

        <!--[automation option]-->
        <div class="alert alert-info m-b-20 m-t-25">
            <h6>@lang('lang.automation_option')</h6> @lang('lang.project_automation_info_1'): <span
                class="align-middle text-info font-16" data-toggle="tooltip" title="@lang('lang.project_automation_info_2')"
                data-placement="top"><i class="ti-info-alt"></i></span>
        </div>

        <!--project_automation_create_invoices-->
        <div class="form-group form-group-checkbox row m-b-20">
            <div class="col-12">
                <label class="text-left control-label col-form-label required p-r-3">@lang('lang.automation_invoice_project')</label>
                <span class="text-right p-l-5">
                    <input type="checkbox" id="project_automation_create_invoices"
                        name="project_automation_create_invoices" class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked($automation['project_automation_create_invoices']) }}>
                    <label class="display-inline" for="project_automation_create_invoices"></label>
                </span>
            </div>
        </div>

        <!--invoice creation options-->
        <div class="card-contrast-panel m-l-30 {{ projectAutomationVisibility($automation['project_automation_create_invoices']) }}"
            id="project_automation_create_invoices_settings">

            <h6 class="text-underlined m-b-16">@lang('lang.invoice_creation_options')</h6>

            <!--project_automation_convert_estimates_to_invoices-->
            <div class="form-group form-group-checkbox row">
                <label
                    class="col-sm-12 col-lg-6 col-form-label text-left">@lang('lang.automation_generate_invoice_from_estimates')</label>
                <div class="col-sm-12 col-lg-6 text-left p-t-5">
                    <input type="checkbox" id="project_automation_convert_estimates_to_invoices"
                        {{ runtimePrechecked($automation['project_automation_convert_estimates_to_invoices']) }}
                        name="project_automation_convert_estimates_to_invoices" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="project_automation_convert_estimates_to_invoices"></label>
                </div>
            </div>


            <div class="line"></div>
            <h6 class="text-underlined m-b-16">@lang('lang.invoice_creation_settings')</h6>

            <!--project_automation_invoice_due_date-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-6 text-left control-label col-form-label">@lang('lang.automation_invoice_due_date')</label>
                <div class="col-sm-12 col-lg-6">
                    <input type="number" class="form-control form-control-sm" id="project_automation_invoice_due_date"
                        name="project_automation_invoice_due_date"
                        value="{{  $automation['project_automation_invoice_due_date'] }}">
                </div>
            </div>

            <!--project_automation_invoice_email_client-->
            <div class="form-group form-group-checkbox row">
                <label
                    class="col-sm-12 col-lg-6 col-form-label text-left">@lang('lang.automation_email_invoices_to_client')</label>
                <div class="col-sm-12 col-lg-6 text-left p-t-5">
                    <input type="checkbox" id="project_automation_invoice_email_client"
                        {{ runtimePrechecked($automation['project_automation_invoice_email_client']) }}
                        name="project_automation_invoice_email_client" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="project_automation_invoice_email_client"></label>
                </div>
            </div>


        </div>
    </div>