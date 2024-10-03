@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form">
    
    <!--form text tem-->
    <div class="form-group row">
        <label class="col-3 control-label col-form-label">@lang('lang.site_key')</label>
        <div class="col-9">
            <input type="text" class="form-control form-control-sm" id="settings2_captcha_api_site_key"
                name="settings2_captcha_api_site_key" value="{{ $settings->settings2_captcha_api_site_key ?? '' }}">
        </div>
    </div>

    <!--form text tem-->
    <div class="form-group row">
        <label class="col-3 control-label col-form-label">@lang('lang.secret_key')</label>
        <div class="col-9">
            <input type="text" class="form-control form-control-sm" id="settings2_captcha_api_secret_key"
                name="settings2_captcha_api_secret_key" value="{{ $settings->settings2_captcha_api_secret_key ?? '' }}">
        </div>
    </div>


    <!--Enabled-->
    <div class="form-group form-group-checkbox row">
        <label class="col-3 col-form-label">@lang('lang.enable_feature')</label>
        <div class="col-9 p-t-5">
            <input type="checkbox" id="settings2_captcha_status" name="settings2_captcha_status"
                class="filled-in chk-col-light-blue" {{ runtimePrechecked($settings->settings2_captcha_status) }}>
            <label for="settings2_captcha_status"></label>
        </div>
    </div>

    <div class="alert alert-info">
        @lang('lang.recaptcha_info') - <a href="https://www.google.com/recaptcha/admin/" target="_blank">Google reCAPTCHA</a>
    </div>

    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left js-ajax-ux-request"
            data-url="/settings/recaptcha" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>
@endsection