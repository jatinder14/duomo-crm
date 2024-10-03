@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settings-estimates-automation">


    <!--settings2_estimates_automation_default_status-->
    <div class="form-group row p-b-10">
        <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.estimate_automation_default') <span
                class="align-middle text-info font-16" data-toggle="tooltip"
                title="@lang('lang.estimate_automation_default_info')" data-placement="top"><i
                    class="ti-info-alt"></i></span></label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm select2-preselected"
                id="settings2_estimates_automation_default_status" name="settings2_estimates_automation_default_status"
                data-preselected="{{ $settings->settings2_estimates_automation_default_status ?? ''}}">
                <option></option>
                <option value="enabled">@lang('lang.enabled')</option>
                <option value="disabled">@lang('lang.disabled')</option>
            </select>
        </div>
    </div>


    <!--automation options container-->
    <div class="{{ estimateAutomationVisibility($settings->settings2_estimates_automation_default_status) }}"
        id="settings-automation-options-container">

        <div class="line m-t-20 m-b-10"></div>

        <!--[automation option]-->
        <div class="alert alert-info m-b-20 m-t-25">
            <h6>@lang('lang.automation_option')</h6> @lang('lang.estimate_automation_info_1'): <span
                class="align-middle text-info font-16" data-toggle="tooltip"
                title="@lang('lang.estimate_automation_info_2')" data-placement="top"><i class="ti-info-alt"></i></span>
        </div>

        <!--settings2_estimates_automation_create_project-->
        <div class="form-group form-group-checkbox row m-b-20">
            <div class="col-12">
                <label
                    class="text-left control-label col-form-label required p-r-3">@lang('lang.automation_create_project')</label>
                <span class="text-right p-l-5">
                    <input type="checkbox" id="settings2_estimates_automation_create_project"
                        name="settings2_estimates_automation_create_project" class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked($settings->settings2_estimates_automation_create_project ?? '') }}>
                    <label for="settings2_estimates_automation_create_project" class="display-inline"></label>
                </span>
            </div>
        </div>

        <!--project creation options-->
        <div class="card-contrast-panel m-l-30 {{ estimateAutomationVisibility($settings->settings2_estimates_automation_create_project) }}"
            id="settings_automation_create_project_options">


            <!--settings2_estimates_automation_project_title-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.project_title')</label>
                <div class="col-sm-12 col-lg-8">
                    <input type="text" class="form-control form-control-sm"
                        id="settings2_estimates_automation_project_title"
                        name="settings2_estimates_automation_project_title"
                        value="{{ $settings->settings2_estimates_automation_project_title ?? ''}}">
                </div>
            </div>


            <!--settings2_estimates_automation_project_status-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.automation_create_project_status')</label>
                <div class="col-sm-12 col-lg-4">
                    <select class="select2-basic form-control form-control-sm select2-preselected"
                        id="settings2_estimates_automation_project_status"
                        name="settings2_estimates_automation_project_status"
                        data-preselected="{{ $settings->settings2_estimates_automation_project_status ?? ''}}">
                        <option></option>
                        <option value="not_started">@lang('lang.not_started')</option>
                        <option value="in_progress">@lang('lang.in_progress')</option>
                        <option value="on_hold">@lang('lang.on_hold')</option>
                    </select>
                </div>
            </div>

            <!--estimate_automation_assigned_users-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.automation_assign_project')</label>
                <div class="col-sm-12 col-lg-4">
                    <select name="estimate_automation_assigned_users" id="estimate_automation_assigned_users"
                        class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        @foreach(config('system.team_members') as $user)
                        <option value="{{ $user->id }}"
                            {{ runtimePreselectedInArray($user->id ?? '', $assigned ?? []) }}>
                            {{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!--settings2_estimates_automation_create_tasks-->
            <div class="form-group form-group-checkbox row">
                <label
                    class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_create_tasks_from_line_item')</label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_estimates_automation_create_tasks"
                        {{ runtimePrechecked($settings->settings2_estimates_automation_create_tasks ?? '') }}
                        name="settings2_estimates_automation_create_tasks" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_estimates_automation_create_tasks"></label>
                </div>
            </div>


            <!--settings2_estimates_automation_project_email_client-->
            <div class="form-group form-group-checkbox row">
                <label class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_email_client') <span
                        class="align-middle text-info font-16" data-toggle="tooltip"
                        title="@lang('lang.automation_email_client_project_info')" data-placement="top"><i
                            class="ti-info-alt"></i></span></label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_estimates_automation_project_email_client"
                        {{ runtimePrechecked($settings->settings2_estimates_automation_project_email_client ?? '') }}
                        name="settings2_estimates_automation_project_email_client" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_estimates_automation_project_email_client"></label>
                </div>
            </div>


        </div>

        <!--[automation option]-->
        <div class="alert alert-info m-b-20 m-t-50">
            <h6>@lang('lang.automation_option')</h6> @lang('lang.estimate_automation_info_1'): <span
                class="align-middle text-info font-16" data-toggle="tooltip"
                title="@lang('lang.estimate_automation_info_2')" data-placement="top"><i class="ti-info-alt"></i></span>
        </div>

        <!--settings2_estimates_automation_create_invoice-->
        <div class="form-group form-group-checkbox row m-b-20">
            <div class="col-12">
                <label
                    class="text-left control-label col-form-label p-r-3 required">@lang('lang.automation_create_invoice')</label>
                <span class="text-right p-l-5">
                    <input type="checkbox" id="settings2_estimates_automation_create_invoice"
                        name="settings2_estimates_automation_create_invoice" class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked($settings->settings2_estimates_automation_create_invoice ?? '') }}>
                    <label for="settings2_estimates_automation_create_invoice" class="display-inline"></label>
                </span>
            </div>
        </div>

        <!--invoice creation options-->
        <div class="card-contrast-panel m-l-30 {{ estimateAutomationVisibility($settings->settings2_estimates_automation_create_invoice) }}"
            id="settings_automation_create_invoice_options">

            <!--item-->
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-4 text-left control-label col-form-label">@lang('lang.automation_invoice_due_date')</label>
                <div class="col-sm-12 col-lg-4">
                    <input type="text" class="form-control form-control-sm"
                        id="settings2_estimates_automation_invoice_due_date"
                        name="settings2_estimates_automation_invoice_due_date"
                        value="{{ $settings->settings2_estimates_automation_invoice_due_date ?? '' }}">
                </div>
            </div>

            <!--settings2_estimates_automation_invoice_email_client-->
            <div class="form-group form-group-checkbox row">
                <label class="col-sm-12 col-lg-4 col-form-label text-left">@lang('lang.automation_email_client') <span
                        class="align-middle text-info font-16" data-toggle="tooltip"
                        title="@lang('lang.automation_email_client_invoice_info')" data-placement="top"><i
                            class="ti-info-alt"></i></span></label>
                <div class="col-sm-12 col-lg-8 text-left p-t-5">
                    <input type="checkbox" id="settings2_estimates_automation_invoice_email_client"
                        {{ runtimePrechecked($settings->settings2_estimates_automation_invoice_email_client ?? '') }}
                        name="settings2_estimates_automation_invoice_email_client" class="filled-in chk-col-light-blue">
                    <label class="p-l-30" for="settings2_estimates_automation_invoice_email_client"></label>
                </div>
            </div>

        </div>

    </div>


    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/estimates/automation" data-loading-target="" data-ajax-type="PUT" data-type="form"
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