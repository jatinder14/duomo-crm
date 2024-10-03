<div class="row">
    <div class="col-lg-12">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="date" class="form-control form-control-sm" placeholder="@lang('lang.date')"
                    name="holiday_date" id="holiday_date" value="{{ $holiday->holiday_date ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.description')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="form-control form-control-sm" placeholder="@lang('lang.description')" name="holiday_description"
                    id="holiday_description">{{ $holiday->holiday_description ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>
