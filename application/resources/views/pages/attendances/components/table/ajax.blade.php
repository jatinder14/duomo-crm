@foreach ($attendances as $attendance)
    <!--each row-->
    <tr id="attendance_{{ $attendance->attendance_id }}">
        @if (config('visibility.attendances_col_checkboxes'))
            <td class="attendances_col_checkbox checkitem" id="attendances_col_checkbox_{{ $attendance->attendance_id }}">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-attendances-{{ $attendance->attendance_id }}"
                        name="ids[{{ $attendance->attendance_id }}]"
                        class="listcheckbox listcheckbox-attendances filled-in chk-col-light-blue"
                        data-actions-container-class="attendances-checkbox-actions-container"
                        {{ runtimeDisabledAttendancesCheckboxes(config('visibility.attendances_disable_actions')) }}
                        {{-- @if ($attendance->attendance_status == '1') disabled @endif --}}>
                    <label for="listcheckbox-attendances-{{ $attendance->attendance_id }}"></label>
                </span>
            </td>
        @endif
        <td class="attendances_col_user">
            @if (config('visibility.attendances_grouped_by_users'))
                <span class="sl-icon-people"></span> {{ cleanLang(__('lang.multiple')) }}
            @else
                <img src="{{ getUsersAvatar($attendance->avatar_directory, $attendance->avatar_filename) }}"
                    alt="user" class="img-circle avatar-xsmall">
                {{ str_limit($attendance->user->full_name ?? runtimeUnkownUser(), 20) }}
            @endif
        </td>
        <!--date-->
        <td class="attendances_col_date">{{ runtimeDate($attendance->date) }}</td>

        <!--in time-->
        <td class="attendances_col_in_time">{!! clean($attendance->in_time, true) !!}</td>

        <!--out time-->
        <td class="attendances_col_out_time">{!! clean($attendance->out_time, true) !!}</td>

        <!--reason-->
        <td class="attendances_col_reason">{!! $attendance->reason ?? '---' !!}</td>

        <!--status-->
        <td class="attendances_col_status">
            <span
                class="label label-outline-{{ $attendance->attendancestatus->attendancestatus_color }}">{{ $attendance->attendancestatus->attendancestatus_title }}</span>
        </td>

        @if (config('visibility.attendances_col_action'))
            <td class="attendances_col_action">
                <span class="list-table-action dropdown  font-size-inherit">
                    @if (config('visibility.attendances_disable_actions'))
                        <span data-toggle="tooltip"
                            title="{{ cleanLang(__('lang.actions_not_available')) }}">---</span>
                    @else
                        @if (config('visibility.action_buttons_delete'))
                            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                                data-url="{{ url('/') }}/attendances/{{ $attendance->attendance_id }}">
                                <i class="sl-icon-trash"></i>
                            </button>
                        @endif
                        @if (config('visibility.action_buttons_edit'))
                            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                data-toggle="modal" data-target="#commonModal"
                                data-url="{{ urlResource('/attendances/' . $attendance->attendance_id . '/edit') }}"
                                data-loading-target="commonModalBody"
                                data-modal-title="{{ cleanLang(__('lang.edit_attendance')) }}"
                                data-action-url="{{ url('/attendances/' . $attendance->attendance_id . '?ref=list') }}"
                                data-action-method="PUT" data-action-ajax-class="js-ajax-request"
                                data-action-ajax-loading-target="attendances-td-container">
                                <i class="sl-icon-note"></i>
                            </button>
                        @endif
                    @endif
                </span>
            </td>
        @endif
    </tr>
    <!--each row-->
@endforeach
