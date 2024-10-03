<!--totals-->
<tr class="report-results-table-totals">
    <td colspan="1">@lang('lang.page_totals')</td>

    <!--count_projects_completed-->
    <td>
        {{ $totals['count_projects_completed'] }}</td>

    <!--count_projects_pending-->
    <td>
        {{ $totals['count_projects_pending'] }}</td>

    <!--sum_invoices_paid-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_invoices_paid']) }}</td>

    <!--sum_invoices_due-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_invoices_due']) }}</td>

    <!--sum_invoices_overdue-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_invoices_overdue']) }}</td>

    <!--sum_estimates_accepted-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_estimates_accepted']) }}</td>

    <!--sum_estimates_declined-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_estimates_declined']) }}</td>

    <!--sum_expenses_invoiced-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_expenses_invoiced']) }}</td>

    <!--sum_expenses_not_invoiced-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_expenses_not_invoiced']) }}</td>


    <!--sum_expenses_not_billable-->
    <td>
        {{ runtimeMoneyFormat($totals['sum_expenses_not_billable']) }}</td>
</tr>

<!--pagination-->
<tr>
    <td class="pagination-container" data-tableexport-display="none" colspan="11">
        <div class="pagination">
            {{ $clients->links('pages.reports.components.misc.pagination') }}
        </div>
    </td>
</tr>