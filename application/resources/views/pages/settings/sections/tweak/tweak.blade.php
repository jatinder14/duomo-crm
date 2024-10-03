@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settingsFormGeneral">

    <!--[reports] - truncate long title-->
    <div class="form-group form-group-checkbox row">
        <label class="col-10 col-form-label text-left">@lang('lang.reports') - @lang('lang.truncate_long_text')</label>
        <div class="col-2 text-right p-t-5">
            <input type="checkbox" id="settings2_tweak_reports_truncate_long_text" name="settings2_tweak_reports_truncate_long_text" class="filled-in chk-col-light-blue" {{ runtimePrechecked($settings->settings2_tweak_reports_truncate_long_text ?? '') }}>
            <label class="p-l-30" for="settings2_tweak_reports_truncate_long_text"></label>
        </div>
    </div>
    

    
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/tweak" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>
@endsection