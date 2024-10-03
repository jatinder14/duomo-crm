<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-estimates">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i
                    class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_estimates')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-estimates"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body p-l-35 p-r-35">

                <!--standard fields-->
                <div class="">
                    <h5>@lang('lang.standard_fields')</h5>
                </div>
                <div class="line"></div>
                <div class="row">


                    <!--bill_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_date]" name="standard_field[bill_date]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_date]">@lang('lang.estimate_date')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_estimateid-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_estimateid]"
                                    name="standard_field[bill_estimateid]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_estimateid]">@lang('lang.estimate_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_company_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_company_name]"
                                    name="standard_field[client_company_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_company_name]">@lang('lang.client')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_clientid-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_clientid]"
                                    name="standard_field[bill_clientid]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_clientid]">@lang('lang.client_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_title-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_title]"
                                    name="standard_field[project_title]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_title]">@lang('lang.project_title')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_projectid-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_projectid]"
                                    name="standard_field[bill_projectid]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_projectid]">@lang('lang.project_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_subtotal-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_subtotal]"
                                    name="standard_field[bill_subtotal]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_subtotal]">@lang('lang.sub_total')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_discount_type-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_discount_type]"
                                    name="standard_field[bill_discount_type]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_discount_type]">@lang('lang.discount_type')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_discount_percentage-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_discount_percentage]"
                                    name="standard_field[bill_discount_percentage]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_discount_percentage]">@lang('lang.discount_percentage')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_discount_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_discount_amount]"
                                    name="standard_field[bill_discount_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_discount_amount]">@lang('lang.discount_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_amount_before_tax-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_amount_before_tax]"
                                    name="standard_field[bill_amount_before_tax]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_amount_before_tax]">@lang('lang.amount_before_tax')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_tax_total_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_tax_total_amount]"
                                    name="standard_field[bill_tax_total_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_tax_total_amount]">@lang('lang.tax')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_adjustment_description-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_adjustment_description]"
                                    name="standard_field[bill_adjustment_description]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_adjustment_description]">@lang('lang.adjustment_description')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_adjustment_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_adjustment_amount]"
                                    name="standard_field[bill_adjustment_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_adjustment_amount]">@lang('lang.adjustment_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_final_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_final_amount]"
                                    name="standard_field[bill_final_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_final_amount]">@lang('lang.estimate_total')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_viewed_by_client-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_viewed_by_client]"
                                    name="standard_field[bill_viewed_by_client]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[bill_viewed_by_client]">@lang('lang.viewed_by_client')</label>
                            </div>
                        </div>
                    </div>

                    <!--bill_status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[bill_status]"
                                    name="standard_field[bill_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[bill_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button" id="export-estimates-button"
                        data-url="{{ urlResource('/export/estimates?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->