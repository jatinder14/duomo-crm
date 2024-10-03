@foreach ($holidays as $holiday)
    <!--each row-->
    <tr id="holiday_{{ $holiday->holiday_id }}">
        @if (config('visibility.holidays_col_checkboxes'))
            <td class="holidays_col_checkbox checkitem" id="holidays_col_checkbox_{{ $holiday->holiday_id }}">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-holidays-{{ $holiday->holiday_id }}"
                        name="ids[{{ $holiday->holiday_id }}]"
                        class="listcheckbox listcheckbox-holidays filled-in chk-col-light-blue"
                        data-actions-container-class="holidays-checkbox-actions-container"
                        {{ runtimeDisabledHolidaysCheckboxes(config('visibility.holidays_disable_actions')) }}
                        {{-- @if ($holiday->holiday_status == '1') disabled @endif --}}>
                    <label for="listcheckbox-holidays-{{ $holiday->holiday_id }}"></label>
                </span>
            </td>
        @endif
        <!--date-->
        <td class="holidays_col_holiday_date">{{ runtimeDate($holiday->holiday_date) }}</td>

        <!--in time-->
        <td class="holidays_col_holiday_description">{!! clean($holiday->holiday_description, true) !!}</td>

        @if (config('visibility.holidays_col_action'))
            <td class="holidays_col_action">
                <span class="list-table-action dropdown  font-size-inherit">
                    @if (config('visibility.holidays_disable_actions'))
                        <span data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}">---</span>
                    @else
                        @if (config('visibility.action_buttons_delete'))
                            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                                data-url="{{ url('/') }}/holidays/{{ $holiday->holiday_id }}">
                                <i class="sl-icon-trash"></i>
                            </button>
                        @endif
                        @if (config('visibility.action_buttons_edit'))
                            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                data-toggle="modal" data-target="#commonModal"
                                data-url="{{ urlResource('/holidays/' . $holiday->holiday_id . '/edit') }}"
                                data-loading-target="commonModalBody"
                                data-modal-title="{{ cleanLang(__('lang.edit_holidays')) }}"
                                data-action-url="{{ url('/holidays/' . $holiday->holiday_id . '?ref=list') }}"
                                data-action-method="PUT" data-action-ajax-class="js-ajax-request"
                                data-action-ajax-loading-target="holidays-td-container">
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
