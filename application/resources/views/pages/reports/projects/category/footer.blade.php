<!--totals-->
<tr class="report-results-table-totals">
    <td class="no-sort" colspan="1">@lang('lang.page_totals')</td>

    <!--count_projects_pending-->
    <td class="no-sort">
        {{ $totals['count_projects_pending'] }}</td>

    <!--count_projects_completed-->
    <td class="no-sort">
        {{ $totals['count_projects_completed'] }}</td>

    <!--count_tasks_due-->
    <td class="no-sort">
        {{ $totals['count_tasks_due'] }}</td>

    <!--count_tasks_completed-->
    <td class="no-sort">{{ $totals['count_tasks_completed'] }}</td>

    <!--sum_hours-->
    <td class="no-sort">
        @if($totals['sum_hours'] == 0)
        0
        @else
        {{ runtimeSecondsWholeHours($totals['sum_hours']) }}:{{ runtimeSecondsWholeMinutesZero($totals['sum_hours']) }}
        @endif
    </td>

    <!--sum_expenses-->
    <td class="data-type-money no-sort" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_expenses'] ?? 0) }}</td>

    <!--sum_invoices-->
    <td class="data-type-money no-sort" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_invoices'] ?? 0) }}
    </td>

    <!--sum_payments-->
    <td class="data-type-money no-sort" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($totals['sum_payments'] ?? 0) }}
    </td>
</tr>