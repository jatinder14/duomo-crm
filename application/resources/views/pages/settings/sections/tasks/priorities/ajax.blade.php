@foreach($priorities as $priority)
<!--each row-->
<tr id="priority_{{ $priority->taskpriority_id }}">
    <td class="priority_col_date">
        <span class="mdi mdi-drag-vertical cursor-pointer"></span>
        <!--sorting data-->
        <input type="hidden" name="sort-priorities[{{ $priority->taskpriority_id }}]" value="{{ $priority->taskpriority_id }}">
        {{ runtimeLang($priority->taskpriority_title) }}
        <!--system initial stage-->
        @if($priority->taskpriority_system_default == 'yes' && $priority->taskpriority_id == 1)
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.required_system_priority')) }}"></span>
        <span class="label label-light-info label-rounded">{{ cleanLang(__('lang.default_priority')) }}</span>

        @endif
    </td>
    <td class="priority_col_count">{{ $priority->count_tasks }}</td>
    <td class="priority_col_color"><span class="bg-{{ $priority->taskpriority_color }}" id="fx-settimgs-tasks-priority">&nbsp;</span>
    </td>
    <td class="priority_col_created_by">
        <img src="{{ getUsersAvatar($priority->avatar_directory, $priority->avatar_filename, $priority->taskpriority_creatorid) }}" alt="user"
            class="img-circle avatar-xsmall">
            {{ checkUsersName($priority->first_name, $priority->taskpriority_creatorid)  }}
        </td>
    <td class="priority_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit" >
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" title="{{ cleanLang(__('lang.edit')) }}"
                data-url="{{ url('/settings/tasks/priorities/'.$priority->taskpriority_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_task_priority')) }}"
                data-action-url="{{ url('/settings/tasks/priorities/'.$priority->taskpriority_id) }}" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="priority-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <button type="button" title="{{ cleanLang(__('lang.move')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" title="{{ cleanLang(__('lang.move')) }}"
                data-url="{{ url('/settings/tasks/move/priority/'.$priority->taskpriority_id) }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.move_tasks')"
                data-action-url="{{ url('/settings/tasks/move/priority/'.$priority->taskpriority_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="commonModalBody">
                <i class="sl-icon-share-alt"></i>
            </button>
            @if($priority->taskpriority_system_default == 'no')
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}" class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_task_priority')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/settings/tasks/priorities/{{ $priority->taskpriority_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i class="sl-icon-trash"></i></span>
            @endif
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->