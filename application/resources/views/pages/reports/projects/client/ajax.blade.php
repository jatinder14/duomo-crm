<!--rows-->
@foreach($projects as $project)
<tr>

    <!--client_company_name-->
    <td><a href="{{ url('clients/'.$project->client_id) }}">{{ $project->client_company_name }}</a>
    </td>

    <!--count_projects-->
    <td>
        {{ $project->count_projects }}</td>

    <!--count_projects_not_started-->
    <td>
        {{ $project->count_projects_not_started }}</td>

    <!--count_projects_on_hold-->
    <td>
        {{ $project->count_projects_on_hold }}</td>

    <!--count_projects_cancelled-->
    <td>
        {{ $project->count_projects_cancelled }}</td>

    <!--count_projects_completed-->
    <td>
        {{ $project->count_projects_completed }}</td>

    <!--count_tasks_due-->
    <td>
        {{ $project->count_tasks_due }}</td>

    <!--count_tasks_completed-->
    <td>
        {{ $project->count_tasks_completed }}</td>


    <!--sum_hours-->
    <td>
        {{ runtimeSecondsWholeHours($project->sum_hours) }}:{{ runtimeSecondsWholeMinutesZero($project->sum_hours) }}</td>
</tr>
@endforeach