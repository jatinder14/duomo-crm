<!--rows-->
@foreach($timesheets as $timesheet)
<tr>

    <!--full_name-->
    <td>
        <img src="{{ getUsersAvatar($timesheet->avatar_directory, $timesheet->avatar_filename, $timesheet->id) }}"
            alt="user" class="img-circle avatar-xsmall">
        {{ checkUsersName($timesheet->first_name, $timesheet->id)  }}
    </td>

    <!--role_name-->
    <td>
        {{ $timesheet->role_name }}</td>


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