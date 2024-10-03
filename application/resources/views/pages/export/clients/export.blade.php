<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-clients">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_clients')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-clients"></i>
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

                    <!--client_company_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_company_name]"
                                    name="standard_field[client_company_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_company_name]">@lang('lang.client_name')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_created-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_created]"
                                    name="standard_field[client_created]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_created]">@lang('lang.date_created')</label>
                            </div>
                        </div>
                    </div>


                    <!--category-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[category]" name="standard_field[category]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[category]">@lang('lang.category')</label>
                            </div>
                        </div>
                    </div>

                    <!--contact_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[contact_name]"
                                    name="standard_field[contact_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[contact_name]">@lang('lang.contact_name')</label>
                            </div>
                        </div>
                    </div>

                    <!--contact_email-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[contact_email]"
                                    name="standard_field[contact_email]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[contact_email]">@lang('lang.contact_email')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_phone-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_phone]"
                                    name="standard_field[client_phone]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[client_phone]">@lang('lang.telephone')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_website-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_website]"
                                    name="standard_field[client_website]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[client_website]">@lang('lang.website')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_vat-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_vat]" name="standard_field[client_vat]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_vat]">@lang('lang.vat_tax_number')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_billing_street-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_billing_street]"
                                    name="standard_field[client_billing_street]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_billing_street]">@lang('lang.street')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_billing_city-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_billing_city]"
                                    name="standard_field[client_billing_city]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_billing_city]">@lang('lang.city')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_billing_state-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_billing_state]"
                                    name="standard_field[client_billing_state]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_billing_state]">@lang('lang.state')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_billing_zip-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_billing_zip]"
                                    name="standard_field[client_billing_zip]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_billing_zip]">@lang('lang.zipcode')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_billing_country-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_billing_country]"
                                    name="standard_field[client_billing_country]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_billing_country]">@lang('lang.country')</label>
                            </div>
                        </div>
                    </div>

                    <!--invoices-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoices]" name="standard_field[invoices]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[invoices]">@lang('lang.invoices')</label>
                            </div>
                        </div>
                    </div>

                    <!--payments-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[payments]" name="standard_field[payments]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[payments]">@lang('lang.payments')</label>
                            </div>
                        </div>
                    </div>

                    <!--status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_status]"
                                    name="standard_field[client_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[client_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--custon fields-->
                <div class="m-t-30">
                    <h5>@lang('lang.custom_fields')</h5>
                </div>
                <div class="line"></div>
                <div class="row">
                    @foreach($fields as $field)
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="custom_field[{{ $field->customfields_name }}]"
                                    name="custom_field[{{ $field->customfields_name }}]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30"
                                    for="custom_field[{{ $field->customfields_name }}]">{{ $field->customfields_title }}</label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/export/clients?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->