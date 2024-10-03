<div class="row">
    <div class="col-lg-12">
        <!--name-->
        <div class="form-group row">
            <label
                class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.name')) }}*</label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="taxrate_name" name="taxrate_name"
                    value="{{ $taxrate->taxrate_name ?? '' }}">
            </div>
        </div>
        <!--rate-->
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.rate')) }}
                (%)</label>
            <div class="col-12">
                <input type="number" class="form-control form-control-sm" id="taxrate_value" name="taxrate_value"
                    {{ runtimeSystemTaxRate($taxrate->taxrate_type ?? '') }}
                    value="{{ $taxrate->taxrate_value ?? '' }}">
            </div>
        </div>

        <!--taxrate_status-->
        <div class="form-group row">
            <label
                class="col-sm-12 text-left control-label col-form-label">@lang('lang.status')</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm select2-preselected" id="taxrate_status" {{ runtimeSystemTaxRate($taxrate->taxrate_type ?? '') }}
                    name="taxrate_status" data-preselected="{{ $taxrate->taxrate_status ?? 'enabled'}}">
                    <option></option>
                    <option value="enabled">@lang('lang.enabled')</option>
                    <option value="disabled">@lang('lang.disabled')</option>
                </select>
            </div>
        </div>

    </div>
</div>