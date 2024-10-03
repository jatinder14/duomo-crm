
<!--totals-->
<tr class="report-results-table-totals">
    <td colspan="3">@lang('lang.page_totals')</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_before_tax'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_tax'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_discount'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_adjustment'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_final_amount'] ?? 0) }}
    </td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_payments'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_balance_due'] ?? 0) }}
    </td>
    <td>---</td>
</tr>

<!--pagination-->
<tr>
    <td class="pagination-container" data-tableexport-display="none" colspan="11">
        <div class="pagination">
            {{ $invoices->links('pages.reports.components.misc.pagination') }}
        </div>
    </td>
</tr>