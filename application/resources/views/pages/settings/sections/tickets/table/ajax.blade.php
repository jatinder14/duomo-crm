@foreach($statuses as $status)
<!--each row-->
<tr id="status_{{ $status->ticketstatus_id }}">
    <td class="status_col_date">
        <span class="mdi mdi-drag-vertical cursor-pointer"></span>
        <!--sorting data-->
        <input type="hidden" name="sort-stages[{{ $status->ticketstatus_id }}]" value="{{ $status->ticketstatus_id }}">
        {{ runtimeLang($status->ticketstatus_title) }}

        <!--ticketstatus_use_for_team_replied-->
        @if($status->ticketstatus_use_for_team_replied == 'yes')
        <span class="sl-icon-action-redo text-info p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.tickets_apply_when_staff_replied')) }}"></span>
        @endif

        <!--ticketstatus_use_for_client_replied-->
        @if($status->ticketstatus_use_for_client_replied == 'yes')
        <span class="sl-icon-action-undo text-purple p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.tickets_apply_when_customer_replied')) }}"></span>
        @endif

        <!--system initial stage-->
        @if($status->ticketstatus_system_default == 'yes' && $status->ticketstatus_id == 1)
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.required_system_status')) }}"></span>
        <span class="label label-light-info label-rounded">{{ cleanLang(__('lang.new_status')) }}</span>

        @endif
        <!--system initial stage-->
        @if($status->ticketstatus_system_default == 'yes' && $status->ticketstatus_id == 2)
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.required_system_status')) }}"></span>
        <span class="label label-light-info label-rounded">{{ cleanLang(__('lang.closed_status')) }}</span>
        @endif

    </td>
    <td class="status_col_count">{{ $status->count_tickets }}</td>
    <td class="status_col_color"><span class="bg-{{ $status->ticketstatus_color }}"
            id="fx-settimgs-tickets-status">&nbsp;</span>
    </td>
    <td class="status_col_created_by">
        <img src="{{ getUsersAvatar($status->avatar_directory, $status->avatar_filename, $status->ticketstatus_creatorid) }}"
            alt="user" class="img-circle avatar-xsmall">
        {{ checkUsersName($status->first_name, $status->ticketstatus_creatorid)  }}
    </td>
    <td class="status_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" title="{{ cleanLang(__('lang.edit')) }}"
                data-url="{{ url('/settings/tickets/statuses/'.$status->ticketstatus_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_ticket_status')) }}"
                data-action-url="{{ url('/settings/tickets/statuses/'.$status->ticketstatus_id) }}"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="status-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <button type="button" title="{{ cleanLang(__('lang.move')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" title="{{ cleanLang(__('lang.move')) }}"
                data-url="{{ url('/settings/tickets/move/'.$status->ticketstatus_id) }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.move_tickets')"
                data-action-url="{{ url('/settings/tickets/move/'.$status->ticketstatus_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="commonModalBody">
                <i class="sl-icon-share-alt"></i>
            </button>


            <button type="button" title="{{ cleanLang(__('lang.settings')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-info btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" title="{{ cleanLang(__('lang.move')) }}"
                data-url="{{ url('/settings/tickets/statuses/'.$status->ticketstatus_id.'/settings/') }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.settings')"
                data-action-url="{{ url('/settings/tickets/statuses/'.$status->ticketstatus_id.'/settings/') }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="commonModalBody">
                <i class="sl-icon-wrench"></i>
            </button>

            @if($status->ticketstatus_system_default == 'no')
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_ticket_status')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/settings/tickets/statuses/{{ $status->ticketstatus_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-trash"></i></span>
            @endif

        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->