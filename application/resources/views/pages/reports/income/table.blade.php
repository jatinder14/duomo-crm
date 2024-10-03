<!--table-->
<div class="table-responsive report-results-table-container" id="report-results-container">
    <table class="table table-hover no-wrap" id="report-results-table">
        <thead>
            <tr>

                <!--income-->
                <th class="col_report_income_month w-49">@lang('lang.month')</th>

                <!--income-->
                <th class="col_report_income_income w-17">@lang('lang.income')</th>

                <!--income-->
                <th class="col_report_income_expenses w-17">@lang('lang.expenses')</th>

                <!--income-->
                <th class="col_report_income_total w-17">@lang('lang.profit')</th>

            </tr>
        </thead>
        <tbody id="report-results-ajax-container">
            @foreach($report as $key => $value)
            @if(is_numeric($key))
            <tr>
                <td>
                    {{ $value['month'] }}
                </td>
                <td>
                    {{ runtimeMoneyFormat($value['income']) }}
                </td>
                <td>
                    {{ runtimeMoneyFormat($value['expenses']) }}
                </td>
                <td>
                    {{ runtimeMoneyFormat($value['profit']) }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <td class="font-weight-500">
                @lang('lang.total')
            </td>
            <td class="font-weight-500">
                {{ runtimeMoneyFormat($report['totals']['income']) }}
            </td>
            <td class="font-weight-500">
                {{ runtimeMoneyFormat($report['totals']['expenses']) }}
            </td>
            <td class="font-weight-500">
                {{ runtimeMoneyFormat($report['totals']['profit']) }}
            </td>
        </tfoot>
    </table>

</div>