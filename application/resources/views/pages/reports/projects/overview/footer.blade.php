
<!--totals-->
<tr class="report-results-table-totals">
    <td colspan="3">@lang('lang.page_totals')</td>

    <!--count_tasks_due-->
    <td>
        {{ $totals['count_tasks_due'] }}</td>

    <!--count_tasks_completed-->
    <td>{{ $totals['count_tasks_completed'] }}</td>

    <!--sum_hours-->
    <td>
        @if($totals['sum_hours'] == 0)
        0
        @else
        {{ runtimeSecondsWholeHours($totals['sum_hours']) }}:{{ runtimeSecondsWholeMinutesZero($totals['sum_hours']) }}
        @endif
    </td>

    <!--sum_expenses-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_expenses'] ?? 0) }}</td>

    <!--sum_invoices-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_invoices'] ?? 0) }}
    </td>

    <!--sum_payments-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_payments'] ?? 0) }}
    </td>
    <td>---</td>
</tr>

<!--pagination-->
<tr>
    <td class="pagination-container" data-tableexport-display="none" colspan="11">
        <div class="pagination">
            {{ $projects->links('pages.reports.components.misc.pagination') }}
        </div>
    </td>
</tr>