@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form">

    <!--settings2_file_bulk_download-->
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.bulk_file_download')</label>
        <div class="col-sm-12">
            <select class="select2-basic form-control form-control-sm select2-preselected"
                id="settings2_file_bulk_download" name="settings2_file_bulk_download"
                data-preselected="{{ $settings->settings2_file_bulk_download ?? ''}}">
                <option value="enabled">@lang('lang.enabled')</option>
                <option value="disabled">@lang('lang.disabled')</option>
            </select>
        </div>
    </div>


    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton"
            class="btn btn-rounded-x btn-danger waves-effect text-left js-ajax-ux-request"
            data-url="/settings/files/general" data-loading-target="" data-ajax-type="PUT" data-type="form"
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