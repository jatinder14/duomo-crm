@foreach($templates as $template)
<!--each row-->
<tr id="template_{{ $template->proposal_template_id }}">

    <!--title-->
    <td class="col_proposal_template_title">
        <a href="javascript:void(0);" class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal"
            data-url="{{ urlResource('/templates/proposals/'.$template->proposal_template_id.'/edit') }}"
            data-loading-target="commonModalBody" data-modal-title="@lang('lang.edit_item')"
            data-action-url="{{ urlResource('/templates/proposals/'.$template->proposal_template_id) }}"
            data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request" data-modal-size="modal-xxl"
            data-action-ajax-loading-target="proposals-td-container">{{ str_limit($template->proposal_template_title ?? '---', 80) }}</a>
    </td>

    <!--proposal_template_created-->
    <td class="col_proposal_template_created">
        {{ runtimeDate($template->proposal_template_created) }}
    </td>

    <!--created by-->
    <td class="col_created_by">
        <img src="{{ getUsersAvatar($template->avatar_directory, $template->avatar_filename, $template->proposal_template_creatorid) }}"
            alt="user" class="img-circle avatar-xsmall">
        {{ checkUsersName($template->first_name, $template->proposal_template_creatorid)  }}
    </td>


    <!--actions-->
    <td class="col_proposals_actions actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if(auth()->user()->role->role_templates_proposals > 2)
            <button type="button" title="@lang('lang.delete')"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/templates/proposals/'.$template->proposal_template_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(auth()->user()->role->role_templates_proposals > 1)
            <button type="button" title="@lang('lang.edit')"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/templates/proposals/'.$template->proposal_template_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.edit_proposal')"
                data-action-url="{{ urlResource('/templates/proposals/'.$template->proposal_template_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request" data-modal-size="modal-xxl"
                data-action-ajax-loading-target="proposals-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->