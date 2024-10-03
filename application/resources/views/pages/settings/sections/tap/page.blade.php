@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form">

    <!--settings2_tap_secret_key-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label required">API - @lang('lang.secret_key')*
            <span class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                title="@lang('lang.gateway_general_info')" data-placement="top"><i class="ti-info-alt"></i></span>
        </label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings2_tap_secret_key"
                name="settings2_tap_secret_key" value="{{ $settings->settings2_tap_secret_key ?? '' }}">
        </div>
    </div>

    <!--settings2_tap_publishable_key-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label required">API - @lang('lang.publishable_key')*
            <span class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                title="@lang('lang.gateway_general_info')" data-placement="top"><i class="ti-info-alt"></i></span>
        </label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings2_tap_publishable_key"
                name="settings2_tap_publishable_key" value="{{ $settings->settings2_tap_publishable_key ?? '' }}">
        </div>
    </div>

    <!--settings2_tap_currency_code-->
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label required">@lang('lang.currency')*</label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm select2-preselected"
                id="settings2_tap_currency_code" name="settings2_tap_currency_code"
                data-preselected="{{ $settings->settings2_tap_currency_code ?? 'KWD'}}">
                <option></option>
                <option value="KWD">KWD</option>
                <option value="BHD">BHD</option>
                <option value="SAR">SAR</option>
                <option value="AED">AED</option>
                <option value="OMR">OMR</option>
                <option value="QAR">QAR</option>
                <option value="EGP">EGP</option>
                <option value="GBP">GBP</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
    </div>


    <!--settings2_tap_language-->
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label required">@lang('lang.language')*</label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm select2-preselected" id="settings2_tap_language"
                name="settings2_tap_language" data-preselected="{{ $settings->settings2_tap_language ?? 'KWD'}}">
                <option></option>
                <option value="en">English</option>
                <option value="ar">Arabic</option>
            </select>
        </div>
    </div>


    <!--display name-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label required">{{ cleanLang(__('lang.display_name')) }}*
            <span class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                title="{{ cleanLang(__('lang.display_name_info')) }}" data-placement="top"><i
                    class="ti-info-alt"></i></span>
        </label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings2_tap_display_name"
                name="settings2_tap_display_name" value="{{ $settings->settings2_tap_display_name ?? '' }}">
        </div>
    </div>


    <!--Enabled-->
    <div class="form-group form-group-checkbox row">
        <label class="col-3 col-form-label" title="Foo">{{ cleanLang(__('lang.enable_payment_method')) }}</label>
        <div class="col-9 p-t-5">
            <input type="checkbox" id="settings2_tap_status" name="settings2_tap_status"
                class="filled-in chk-col-light-blue" {{ runtimePrechecked($settings->settings2_tap_status) }}>
            <label for="settings2_tap_status"></label>
        </div>
    </div>


    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton"
            class="btn btn-rounded-x btn-danger waves-effect text-left js-ajax-ux-request" data-url="/settings/tap"
            data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">@lang('lang.save_changes')</button>
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