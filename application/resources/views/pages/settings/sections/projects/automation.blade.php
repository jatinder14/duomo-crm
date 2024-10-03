@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settings-projects-automation">


    <!--settings2_projects_automation_default_status-->
    <div class="form-group row p-b-10">
        <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.project_automation_default') <span
                class="align-middle text-info font-16" data-toggle="tooltip"
                title="@lang('lang.project_automation_default_info')" data-placement="top"><i
                    class="ti-info-alt"></i></span></label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm select2-preselected"
                id="automation_default_status" name="settings2_projects_automation_default_status"
                data-preselected="{{ $settings->settings2_projects_automation_default_status ?? ''}}">
                <option></option>
                <option value="enabled">@lang('lang.enabled')</option>
                <option value="disabled">@lang('lang.disabled')</option>
            </select>
        </div>
    </div>


    <!--automation options container-->
    <div class="{{ projectAutomationVisibility($settings->settings2_projects_automation_default_status) }}"
        id="automation-options-container">

        <div class="line m-t-20 m-b-10"></div>

        <!--[automation option]-->
        <div class="alert alert-info m-b-20 m-t-25">
            <h6>@lang('lang.automation_option')</h6> @lang('lang.project_automation_info_1'): <span
                class="align-middle text-info font-16" data-toggle="tooltip"
                title="@lang('lang.project_automation_info_2')" data-placement="top"><i class="ti-info-alt"></i></span>
        </div>

        <!--settings2_projects_automation_create_invoices-->
        <div class="form-group form-group-checkbox row m-b-20">
            <div class="col-12">
                <label
                    class="text-left control-label col-form-label p-r-3 required">@lang('lang.automation_invoice_project')</label>
                <span class="text-right p-l-5">
                    <input type="checkbox" id="settings2_projects_automation_create_invoices"
                        name="settings2_projects_automation_create_invoices" class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked($settings->settings2_projects_automation_create_invoices ?? '') }}>
                    <label for="settings2_projects_automation_create_invoices" class="display-inline"></label>
                </span>
            </div>
        </div>

        <!--invoice creation options-->
        <div class="card-contrast-panel m-l-30 {{ projectAutomationVisibility($settings->settings2_projects_automation_create_invoices) }}"
            id="project_automation_create_invoices_settings">

            <h6 class="text-underlined m-b-16">@lang('lang.invoice_creation_options')</h6>

            <!--settings2_projects_automation_convert_estimates_to_invoices-->
            <div class="form-group form-group-checkbox row m-b-0">
                <label
                    class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_generate_invoice_from_estimates')</label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_projects_automation_convert_estimates_to_invoices"
                        {{ runtimePrechecked($settings->settings2_projects_automation_convert_estimates_to_invoices ?? '') }}
                        name="settings2_projects_automation_convert_estimates_to_invoices"
                        class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_projects_automation_convert_estimates_to_invoices"></label>
                </div>
            </div>


            <!--settings2_projects_automation_skip_draft_estimates-->
            <div class="{{ projectAutomationEstimateStatuses($settings->settings2_projects_automation_convert_estimates_to_invoices) }}"
                id="project_automation_create_invoices_options">
                <div class="form-group form-group-checkbox row p-l-100 m-b-0">
                    <label class="col-sm-12 col-lg-4 col-form-label text-left">-
                        @lang('lang.skip_estimates_with_draft_status')</label>
                    <div class="col-sm-12 col-lg-8 text-left p-t-5">
                        <input type="checkbox" id="settings2_projects_automation_skip_draft_estimates"
                            {{ runtimePrechecked($settings->settings2_projects_automation_skip_draft_estimates ?? '') }}
                            name="settings2_projects_automation_skip_draft_estimates"
                            class="filled-in chk-col-light-blue">
                        <label class="p-l-30" for="settings2_projects_automation_skip_draft_estimates"></label>
                    </div>
                </div>


                <!--settings2_projects_automation_skip_declined_estimates-->
                <div class="form-group form-group-checkbox row p-l-100">
                    <label class="col-sm-12 col-lg-4 col-form-label text-left">-
                        @lang('lang.skip_estimates_with_declined_status')</label>
                    <div class="col-sm-12 col-lg-8 text-left p-t-5">
                        <input type="checkbox" id="settings2_projects_automation_skip_declined_estimates"
                            {{ runtimePrechecked($settings->settings2_projects_automation_skip_declined_estimates ?? '') }}
                            name="settings2_projects_automation_skip_declined_estimates"
                            class="filled-in chk-col-light-blue">
                        <label class="p-l-30" for="settings2_projects_automation_skip_declined_estimates"></label>
                    </div>
                </div>
            </div>


            <!--settings2_projects_automation_invoice_unbilled_hours-->
            <!--TODO-->
            @if(config('visibility.foooo_bar'))
            <div class="form-group form-group-checkbox row">
                <label
                    class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_invoice_unbilled_hours')</label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_projects_automation_invoice_unbilled_hours"
                        {{ runtimePrechecked($settings->settings2_projects_automation_invoice_unbilled_hours ?? '') }}
                        name="settings2_projects_automation_invoice_unbilled_hours"
                        class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_projects_automation_invoice_unbilled_hours"></label>
                </div>
            </div>
            @endif

            <!--TODO-->
            @if(config('visibility.foooo_bar'))
            <div class="{{ projectAutomationHourlyVisibility($settings->settings2_projects_automation_invoice_unbilled_hours ?? '') }}"
                id="project_automation_invoice_hourly_rate_container">

                <div class="line"></div>

                <h6 class="text-underlined m-b-16">@lang('lang.hourly_billing_settings')</h6>

                <!--settings2_projects_automation_invoice_hourly_rate-->
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.default_hourly_rate')</label>
                    <div class="col-sm-12 col-lg-4">
                        <input type="number" class="form-control form-control-sm"
                            id="settings2_projects_automation_invoice_hourly_rate"
                            name="settings2_projects_automation_invoice_hourly_rate"
                            value="{{ $settings->settings2_projects_automation_invoice_hourly_rate ?? '' }}">
                    </div>
                </div>

                <!--settings2_projects_automation_invoice_hourly_tax_1-->
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.default_tax')</label>
                    <div class="col-sm-12 col-lg-4">
                        <select class="select2-basic form-control form-control-sm select2-preselected"
                            id="settings2_projects_automation_invoice_hourly_tax_1"
                            name="settings2_projects_automation_invoice_hourly_tax_1"
                            data-preselected="{{ $settings->settings2_projects_automation_invoice_hourly_tax_1 ?? ''}}">
                            <option></option>
                            @foreach($taxrates as $taxrate)
                            <option value="{{ $taxrate->taxrate_id }}">{{ $taxrate->taxrate_name }} -
                                {{ $taxrate->taxrate_value }}%</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            @endif

            <div class="line"></div>
            <h6 class="text-underlined m-b-16">@lang('lang.invoice_creation_settings')</h6>

            <!--automation_invoice_due_date-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.automation_invoice_due_date')</label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm"
                        id="settings2_projects_automation_invoice_due_date"
                        name="settings2_projects_automation_invoice_due_date"
                        value="{{ $settings->settings2_projects_automation_invoice_due_date ?? '' }}">
                </div>
            </div>

            <!--settings2_projects_automation_invoice_email_client-->
            <div class="form-group form-group-checkbox row">
                <label
                    class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_email_invoices_to_client')</label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_projects_automation_invoice_email_client"
                        {{ runtimePrechecked($settings->settings2_projects_automation_invoice_email_client ?? '') }}
                        name="settings2_projects_automation_invoice_email_client" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_projects_automation_invoice_email_client"></label>
                </div>
            </div>


        </div>
    </div>


    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/projects/automation" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>

@if(config('system.settings_type') == 'standalone')
<!--[standalone] - settings documentation help-->
<a href="https://growcrm.io/documentation" target="_blank" class="btn btn-sm btn-info help-documentation"><i
        class="ti-info-alt"></i>
    {{ cleanLang(__('lang.help_documentation')) }}
</a>
@endif

@endsection