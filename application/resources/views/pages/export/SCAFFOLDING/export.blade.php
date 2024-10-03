<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-invoices">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_clients')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-invoices"></i>
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

                    <!--invoice_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoice_id]" name="standard_field[invoice_id]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[invoice_id]">@lang('lang.invoice_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--invoice_fooo-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoice_fooo]"
                                    name="standard_field[invoice_fooo]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[invoice_fooo]">@lang('lang.invoice_id')</label>
                            </div>
                        </div>
                    </div>

                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/export/invoices?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->