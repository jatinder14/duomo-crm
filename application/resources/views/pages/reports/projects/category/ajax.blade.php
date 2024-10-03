<!--rows-->
@foreach($projects as $project)
<tr>

    <!--category_name-->
    <td>{{ str_limit_reports($project['category_name'] ?? '---', 40) }}
    </td>

    <!--count_projects_pending-->
    <td>
        {{ $project['count_projects_pending'] }}</td>

    <!--count_projects_completed-->
    <td>
        {{ $project['count_projects_completed'] }}</td>

    <!--count_tasks_due-->
    <td>
        {{ $project['count_tasks_due'] }}</td>

    <!--count_tasks_completed-->
    <td>
        {{ $project['count_tasks_completed'] }}</td>


    <!--sum_hours-->
    <td>
        {{ runtimeSecondsWholeHours($project['sum_hours']) }}:{{ runtimeSecondsWholeMinutesZero($project['sum_hours']) }}</td>

    <!--sum_expenses-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project['sum_expenses']) }}</td>

    <!--sum_invoices-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project['sum_invoices']) }}</td>

    <!--sum_payments-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project['sum_payments']) }}</td>
</tr>
@endforeach