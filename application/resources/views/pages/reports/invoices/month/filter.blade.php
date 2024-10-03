<div class="reports-list-page-filter-container">
    <form class="form-inline row gy-2 gx-3 align-items-center" id="reports-list-page-filter-form">

        <!--item-->
        <div class="form-group row">
            <select class="select2-basic form-control form-control-sm select2-preselected" id="filter_year"
                style="width:130px;" name="filter_year" data-preselected="{{ now()->year }}" data-width="resolve">
                <option></option>
                <optgroup label="@lang('lang.year')">
                    <option value="all">@lang('lang.all')</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <!--status-->
        <div class="form-group row">
            <select name="filter_bill_status" id="filter_bill_status" style="width:200px;"
                class="form-control  form-control-sm select2-basic select2-multiple select2-hidden-accessible select2-preselected"
                multiple="multiple" tabindex="-1" aria-hidden="true" data-width="resolve" data-preselected="all"
                data-placeholder="Status (All)">
                <option></option>
                <option value="paid">@lang('lang.paid')</option>
                <option value="due">@lang('lang.due')</option>
                <option value="overdue">@lang('lang.overdue')</option>
            </select>
        </div>


        <!--form buttons-->
        <div class="col-auto">
            <input type="hidden" name="report-form" value="filter">
            <button type="submit" id="submitButton" class="btn btn-info btn-sm waves-effect text-left ajax-request"
                data-url="{{ url('report/invoices/month?action=load') }}" data-loading-target="report-results-container"
                data-ajax-type="POST" data-form-id="reports-list-page-filter-form"
                data-on-start-submit-button="disable">@lang('lang.update_report')</button>
        </div>
    </form>
</div>