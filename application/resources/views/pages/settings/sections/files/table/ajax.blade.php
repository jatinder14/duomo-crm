@foreach($folders as $folder)
<!--each row-->
<tr id="folder_{{ $folder->filefolder_id }}">

    <!--filefolder_name-->
    <td class="col_filefolder_name">
        {{ $folder->filefolder_name }}
        @if($folder->filefolder_default == 'yes')
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.system_default')) }}"></span>
        @endif
    </td>

    <!--filefolder_created-->
    <td class="col_filefolder_created">
        {{ runtimeDate($folder->filefolder_created) }}
    </td>

    <!--filefolder_creatorid-->
    <td class="col_filefolder_creatorid">
        <img src="{{ getUsersAvatar($folder->avatar_directory, $folder->avatar_filename, $folder->filefolder_creatorid) }}"
            alt="user" class="img-circle avatar-xsmall">
        {{ checkUsersName($folder->first_name, $folder->filefolder_creatorid)  }}
    </td>

    <td class="folders_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ url('/settings/files/defaultfolders/'.$folder->filefolder_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_folder')) }}"
                data-action-url="{{ url('/settings/files/defaultfolders/'.$folder->filefolder_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="folders-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @if($folder->filefolder_default == 'no')
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/settings/files/defaultfolders/'.$folder->filefolder_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled {{ runtimePlaceholdeActionsButtons() }}" data-toggle="tooltip"
                title="{{ cleanLang(__('lang.actions_not_available')) }}"><i class="sl-icon-trash"></i></span>
            @endif
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->