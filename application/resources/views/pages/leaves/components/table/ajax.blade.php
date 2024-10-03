@foreach ($leaves as $leave)
    <!--each row-->
    <tr id="leave_{{ $leave->leave_id }}">
        @if (config('visibility.leaves_col_checkboxes'))
            <td class="leaves_col_checkbox checkitem" id="leaves_col_checkbox_{{ $leave->leave_id }}">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-leaves-{{ $leave->leave_id }}"
                        name="ids[{{ $leave->leave_id }}]"
                        class="listcheckbox listcheckbox-leaves filled-in chk-col-light-blue"
                        data-actions-container-class="leaves-checkbox-actions-container"
                        {{ runtimeDisabledleavesCheckboxes(config('visibility.leaves_disable_actions')) }}
                        {{-- @if ($leave->leave_status == '1') disabled @endif --}}>
                    <label for="listcheckbox-leaves-{{ $leave->leave_id }}"></label>
                </span>
            </td>
        @endif
        <td class="leaves_col_user">
            @if (config('visibility.leaves_grouped_by_users'))
                <span class="sl-icon-people"></span> {{ cleanLang(__('lang.multiple')) }}
            @else
                <img src="{{ getUsersAvatar($leave->avatar_directory, $leave->avatar_filename) }}" alt="user"
                    class="img-circle avatar-xsmall">
                {{ str_limit($leave->creator->full_name ?? runtimeUnkownUser(), 20) }}
            @endif
        </td>

        <!--date-->
        <td class="leaves_col_date">
            {{ runtimeDate($leave->leave_date_from) }}
            {{ $leave->leave_date_from == $leave->leave_date_to ? '' : '- ' . runtimeDate($leave->leave_date_to) }}
        </td>

        <!--tableconfig_column_5 [leave_category]-->
        <td class="col_leave_status {{ config('table.tableconfig_column_5') }} tableconfig_column_5"
            id="leaves_col_stage_{{ $leave->leave_id }}">
            <span class="label {{ bootstrapColors($leave->leavecategory->leavecategory_color ?? '', 'label') }}">
                <!--notes: alternatve lang for leave status will need to be added manally by end user in lang files-->
                {{ runtimeLang($leave->leavecategory->leavecategory_title ?? '') }}</span>
        </td>

        <!--reason-->
        <td class="leaves_col_reason">{!! $leave->leave_reason ?? '---' !!}</td>

        <!--tableconfig_column_5 [leave_status]-->
        <td class="col_leave_status {{ config('table.tableconfig_column_5') }} tableconfig_column_5"
            id="leaves_col_stage_{{ $leave->leave_id }}">
            <span class="label {{ bootstrapColors($leave->leavestatus->leavestatus_color ?? '', 'label') }}">
                <!--notes: alternatve lang for leave status will need to be added manally by end user in lang files-->
                {{ runtimeLang($leave->leavestatus->leavestatus_title ?? '') }}</span>
        </td>

        <!--comment-->
        <td class="leaves_col_comment">{!! $leave->leave_comment ?? '---' !!}</td>

        <!--date-->
        <td class="leaves_col_date">
            {{ runtimeDate($leave->leave_created) }}
        </td>

        @if (config('visibility.leaves_col_action'))
            <td class="leaves_col_action">
                <span class="list-table-action dropdown  font-size-inherit">
                    @if (config('visibility.leaves_disable_actions'))
                        <span data-toggle="tooltip"
                            title="{{ cleanLang(__('lang.actions_not_available')) }}">---</span>
                    @else
                        @if (config('visibility.action_buttons_delete'))
                            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                                data-url="{{ url('/') }}/leaves/{{ $leave->leave_id }}">
                                <i class="sl-icon-trash"></i>
                            </button>
                        @endif
                        @if (config('visibility.action_buttons_edit'))
                            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                data-toggle="modal" data-target="#commonModal"
                                data-url="{{ urlResource('/leaves/' . $leave->leave_id . '/edit') }}"
                                data-loading-target="commonModalBody"
                                data-modal-title="{{ cleanLang(__('lang.edit_leaves')) }}"
                                data-action-url="{{ url('/leaves/' . $leave->leave_id . '?ref=list') }}"
                                data-action-method="PUT" data-action-ajax-class="js-ajax-request"
                                data-action-ajax-loading-target="leaves-td-container">
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
