<!--totals-->
<tr class="report-results-table-totals">
    <td colspan="1">@lang('lang.page_totals')</td>

    <!--count_projects-->
    <td>
        {{ $totals['count_projects'] }}</td>

    <!--count_projects_not_started-->
    <td>
        {{ $totals['count_projects_not_started'] }}</td>

    <!--count_projects_on_hold-->
    <td>
        {{ $totals['count_projects_on_hold'] }}</td>

    <!--count_projects_cancelled-->
    <td>
        {{ $totals['count_projects_cancelled'] }}</td>

    <!--count_projects_completed-->
    <td>
        {{ $totals['count_projects_completed'] }}</td>

    <!--count_tasks_due-->
    <td>
        {{ $totals['count_tasks_due'] }}</td>

    <!--count_tasks_completed-->
    <td>
        {{ $totals['count_tasks_completed'] }}</td>

    <!--sum_hours-->
    <td>
        {{ runtimeSecondsWholeHours($totals['sum_hours']) }}:{{ runtimeSecondsWholeMinutesZero($totals['sum_hours']) }}</td>
</tr>

<!--pagination-->
<tr>
    <td class="pagination-container" data-tableexport-display="none" colspan="11">
        <div class="pagination">
            {{ $projects->links('pages.reports.components.misc.pagination') }}
        </div>
    </td>
</tr>