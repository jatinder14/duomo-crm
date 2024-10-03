
<!--totals-->
<tr class="report-results-table-totals">
    <td colspan="2">@lang('lang.page_totals')</td>
    <td>---</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_before_tax'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_tax'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_discount'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_adjustment'] ?? 0) }}</td>
    <td class="data-type-money" data-tableexport-xlsxformatid="4">{{ runtimeMoneyFormat($totals['sum_final_amount'] ?? 0) }}
    </td>
    <td>---</td>
</tr>

<!--pagination-->
<tr>
    <td class="pagination-container" data-tableexport-display="none" colspan="11">
        <div class="pagination">
            {{ $estimates->links('pages.reports.components.misc.pagination') }}
        </div>
    </td>
</tr>