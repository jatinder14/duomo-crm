<!--rows-->
@foreach($timesheets as $timesheet)
<tr>

    <!--full_name-->
    <td>
        <a href="{{ url('/clients/'.$timesheet->client_id ) }}">{{ $timesheet->client_company_name ?? '---'  }}</a>
    </td>

    <!--sum_not_invoiced-->
    <td>
        {{ runtimeSecondsWholeHours($timesheet->sum_not_invoiced) }}:{{ runtimeSecondsWholeMinutesZero($timesheet->sum_not_invoiced) }}
    </td>


    <!--sum_invoiced-->
    <td>
        {{ runtimeSecondsWholeHours($timesheet->sum_invoiced) }}:{{ runtimeSecondsWholeMinutesZero($timesheet->sum_invoiced) }}
    </td>

    <!--sum_hours-->
    <td>
        {{ runtimeSecondsWholeHours($timesheet->sum_hours) }}:{{ runtimeSecondsWholeMinutesZero($timesheet->sum_hours) }}
    </td>

</tr>
@endforeach