<!--rows-->
@foreach($projects as $project)
<tr>

    <!--project_id-->
    <td><a href="{{ url('projects/'.$project->project_id) }}">{{ $project->project_id }}</a>
    </td>

    <!--project_title-->
    <td><a href="{{ url('projects/'.$project->project_id) }}">{{ str_limit_reports($project->project_title ?? '---', 40) }}</a>
    </td>

    <!--project_date_due-->
    <td><span class="hidden used-for-sorting">{{ $project->timestamp_project_date_due }}</span>{{ runtimeDate($project->project_date_due) }}</td>

    <!--count_tasks_due-->
    <td>
        {{ $project->count_tasks_due }}</td>

    <!--count_tasks_completed-->
    <td>
        {{ $project->count_tasks_completed }}</td>


    <!--sum_hours-->
    <td>
        {{ runtimeSecondsWholeHours($project->sum_hours) }}:{{ runtimeSecondsWholeMinutesZero($project->sum_hours) }}</td>

    <!--sum_expenses-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project->sum_expenses) }}</td>

    <!--sum_invoices-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project->sum_invoices) }}</td>

    <!--sum_payments-->
    <td class="data-type-money" data-tableexport-xlsxformatid="4">
        {{ runtimeMoneyFormat($project->sum_payments) }}</td>

    <!--project_status-->
    <td> <span class="label {{ runtimeProjectStatusColors($project->project_status, 'label') }}">{{
                    runtimeLang($project->project_status) }}</span></td>
</tr>
@endforeach
