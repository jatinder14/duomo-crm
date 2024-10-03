@foreach($projects as $project)
<!--each row-->
<tr id="projects_{{ $project->project_id }}">
    <td class="projects_col_title">
        <a href="{{ url('templates/projects/'.$project->project_id) }}">{{ str_limit($project->project_title ??'---', 30) }}</a>
    </td>
    <td class="projects_col_date">
        {{ runtimeDate($project->project_created) }}
    </td>
    <td class="projects_col_category">
        {{ $project->category_name }}
    </td>
    <td class="projects_col_createby">
        <img src="{{ getUsersAvatar($project->avatar_directory, $project->avatar_filename) }}" alt="user"
        class="img-circle avatar-xsmall"> {{ str_limit($project->first_name ?? runtimeUnkownUser(), 8) }}
    </td>
    <td class="projects_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if(auth()->user()->role->role_templates_projects > 2)
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/templates/projects/{{ $project->project_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(auth()->user()->role->role_templates_projects > 1)
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/templates/projects/'.$project->project_id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.edit_project')) }}" data-action-url="{{ urlResource('/templates/projects/'.$project->project_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="templates-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->