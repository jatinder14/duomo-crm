<div class="reports-list-page-filter-container">
    <form class="form-inline row gy-2 gx-3 align-items-center" id="reports-list-page-filter-form">


        <!--start_date-->
        <div class="reports-date-range">
            <div class="form-group row">
                <input type="text" class="form-control form-control-sm pickadate-year" autocomplete="off"  value="{{ now()->year }}"
                    name="filter_year" placeholder="@lang('lang.start_date')">
            </div>
        </div>

        <!--form buttons-->
        <div class="col-auto">
            <input type="hidden" name="report-form" value="filter">
            <button type="submit" id="submitButton" class="btn btn-info btn-sm waves-effect text-left ajax-request"
                data-url="{{ url('report/financial/income-expenses?action=load') }}"
                data-loading-target="report-results-container" data-ajax-type="POST"
                data-form-id="reports-list-page-filter-form"
                data-on-start-submit-button="disable">@lang('lang.update_report')</button>
        </div>
    </form>
</div>