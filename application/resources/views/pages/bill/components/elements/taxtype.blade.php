<div id="invoice-tax-type-content">
    <div class="p-t-10">
        <!--select type-->
        <div class="form-group row m-b-0">
            <div class="col-12">
                <select class="custom-select form-control form-control-sm" name="bill_tax_type">
                    <option value="inline" {{ runtimePreselected('inline', $bill['bill_discount_type'] ?? '') }}>
                        @lang('lang.inline_tax')
                    </option>
                    <option value="summary" {{ runtimePreselected('summary', $bill['bill_discount_type'] ?? '') }}>
                        @lang('lang.summary_tax')
                    </option>
                </select>
            </div>
            <div class="form-group row m-t-30 m-b-5">
                <div class="col-12">
                    <div class="alert alert-danger m-l-15 m-r-15 -m-t-30">
                        <h5 class="text-danger"><i class="ti-alert"></i> @lang('lang.warning')</h5>
                        @lang('lang.this_change_will_refresh_page')
                    </div>
                </div>
            </div>
        </div>
        <!--update tax tax type-->
        <div class="form-group text-right">
            <button type="button" class="btn btn-info btn-sm ajax-request" data-type="form"
                data-form-id="invoice-tax-type-content" data-ajax-type="post"
                data-confirm-title="@lang('lang.change_tax_type')"
                data-confirm-text="@lang('lang.this_change_will_refresh_page')"
                data-url="{{ runtimeBillTaxTypeURL($bill) }}">
                @lang('lang.update')
            </button>
        </div>
    </div>
</div>