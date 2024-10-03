@foreach($projects as $project)
<tr id="project_{{ $project->project_id }}">
    @if(config('visibility.projects_col_checkboxes'))
    <td class="projects_col_checkbox checkitem" id="projects_col_checkbox_{{ $project->project_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-projects-{{ $project->project_id }}"
                name="ids[{{ $project->project_id }}]"
                class="listcheckbox listcheckbox-projects filled-in chk-col-light-blue"
                data-actions-container-class="projects-checkbox-actions-container">
            <label for="listcheckbox-projects-{{ $project->project_id }}"></label>
        </span>
    </td>
    @endif
    <!--tableconfig_column_1 [project_id]-->
    <td class="projects_col_id {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
        <a href="{{ _url('/projects/'.$project->project_id) }}">{{ $project->project_id }}</label></a>
    </td>
    <!--tableconfig_column_2 [project_title]-->
    <td class="projects_col_project {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
        <a href="{{ _url('/projects/'.$project->project_id) }}">{{ str_limit($project->project_title ??'---', 20) }}<a />
            <!--automation-->
            @if(auth()->user()->is_team && $project->project_automation_status == 'enabled')
            <span class="sl-icon-energy text-warning p-l-5" data-toggle="tooltip"
                title="@lang('lang.project_automation')"></span>
            @endif
    </td>
    <!--tableconfig_column_3 [client_company_name]-->
    @if(config('visibility.projects_col_client'))
    <td class="projects_col_client {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
        <a
            href="/clients/{{ $project->project_clientid }}">{{ str_limit($project->client_company_name ??'---', 12) }}</a>
    </td>
    @endif
    <!--tableconfig_column_4 [project_date_start]-->
    <td class="projects_col_start_date {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
        {{ runtimeDate($project->project_date_start) }}
    </td>
    <!--tableconfig_column_5 [project_date_due]-->
    <td class="projects_col_end_date {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
        {{ runtimeDate($project->project_date_due) }}</td>
    <!--tableconfig_column_6 [tags]-->
    @if(config('visibility.projects_col_tags'))
    <td class="projects_col_tags {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
        <!--tag-->
        @if(count($project->tags ?? []) > 0)
        @foreach($project->tags->take(1) as $tag)
        <span class="label label-outline-default">{{ str_limit($tag->tag_title, 15) }}</span>
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        @if(count($project->tags ?? []) > 1)
        @php $tags = $project->tags; @endphp
        @include('misc.more-tags')
        @endif
        <!--more tags-->
    </td>
    @endif
    <!--tableconfig_column_7 [project_progress]-->
    <td class="projects_col_progress p-r-20 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
        <div class="progress" data-toggle="tooltip" title="{{ $project->project_progress }}%">
            @if($project->project_progress == 100)
            <div class="progress-bar bg-success w-100 h-px-10 font-11 font-weight-500" data-toggle="tooltip"
                title="100%" role="progressbar"></div>
            @else
            <!--dynamic inline style-->
            <div class="progress-bar bg-info h-px-10 font-16 font-weight-500 w-{{ round($project->project_progress) }}"
                role="progressbar"></div>
            @endif
        </div>
    </td>

    <!--tableconfig_column_8 [count_pending_tasks]-->
    <td class="col_count_pending_tasks {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
        {{ $project->count_pending_tasks }}
    </td>

    <!--tableconfig_column_9 [count_tasks_completed]-->
    <td class="col_count_completed_tasks {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
        {{ $project->count_completed_tasks }}
    </td>

    <!--tableconfig_column_10 [sum_invoices_all]-->
    <td class="col_sum_invoices_all {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
        {{ runtimeMoneyFormat($project->sum_invoices_all) }}
    </td>

    <!--tableconfig_column_11 [sum_all_payments]-->
    <td class="col_sum_all_payments {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
        {{ runtimeMoneyFormat($project->sum_all_payments) }}
    </td>

    <!--tableconfig_column_12 [sum_outstanding_balance]-->
    <td class="col_sum_outstanding_balance {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
        {{ runtimeMoneyFormat($project->sum_outstanding_balance) }}
    </td>

    <!--tableconfig_column_13 [project_billing_type]-->
    <td class="col_project_billing_type {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
        {{ runtimeLang($project->project_billing_type) }}
    </td>

    <!--tableconfig_column_14 [project_billing_estimated_hours]-->
    <td class="col_project_billing_estimated_hours {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
        {{ $project->project_billing_estimated_hours }}
    </td>

    <!--tableconfig_column_15 [project_billing_costs_estimate]-->
    <td class="col_project_billing_costs_estimate {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
        {{ runtimeMoneyFormat($project->project_billing_costs_estimate) }}
    </td>

    <!--tableconfig_column_16 [sum_hours]-->
    <td class="col_sum_hours {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
        {{ runtimeSecondsWholeHours($project->sum_hours) }}:{{ runtimeSecondsWholeMinutesZero($project->sum_hours) }}
    </td>

    <!--tableconfig_column_17 [sum_expenses]-->
    <td class="col_sum_expenses {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
        {{ runtimeMoneyFormat($project->sum_expenses) }}
    </td>

    <!--tableconfig_column_18 [count_files]-->
    <td class="col_count_files {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
        {{ $project->count_files }}
    </td>

    <!--tableconfig_column_19 [count_tickets_open]-->
    <td class="count_tickets_open {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
        {{ $project->count_tickets_open }}
    </td>

    <!--tableconfig_column_20 [count_tickets_closed]-->
    <td class="col_count_tickets_closed {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
        {{ $project->count_tickets_closed }}
    </td>


    <!--tableconfig_column_21 [assigned]-->
    @if(config('visibility.projects_col_team'))
    <td class="projects_col_team {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
        <!--assigned users-->
        @if(count($project->assigned ?? []) > 0)
        @foreach($project->assigned->take(2) as $user)
        <img src="{{ $user->avatar }}" data-toggle="tooltip" title="{{ $user->first_name }}" data-placement="top"
            alt="{{ $user->first_name }}" class="img-circle avatar-xsmall">
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--assigned users-->
        <!--more users-->
        @if(count($project->assigned ?? []) > 2)
        @php $more_users_title = __('lang.assigned_users'); $users = $project->assigned; @endphp
        @include('misc.more-users')
        @endif
        <!--more users-->
    </td>
    @endif
    <!--tableconfig_column_22 [project_status]-->
    <td class="projects_col_status {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
        <span
            class="label {{ runtimeProjectStatusColors($project->project_status, 'label') }}">{{ runtimeLang($project->project_status) }}</span>
        <!--archived-->
        @if($project->project_active_state == 'archived' && runtimeArchivingOptions())
        <span class="label label-icons label-icons-default" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.archived')"><i class="ti-archive"></i></span>
        @endif
    </td>
    @if(config('visibility.projects_col_actions'))
    <td class="projects_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            @if(config('visibility.action_buttons_delete'))
            <!--[delete]-->
            @if($project->permission_delete_project)
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ _url('/projects/'.$project->project_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled  {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-trash"></i></span>
            @endif
            @endif
            <!--[edit]-->
            @if(config('visibility.action_buttons_edit'))
            @if($project->permission_edit_project)
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/projects/'.$project->project_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_project')) }}"
                data-action-url="{{ urlResource('/projects/'.$project->project_id) }}" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="projects-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled  {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-note"></i></span>
            @endif
            @if(auth()->user()->role->role_assign_projects == 'yes')
            <button type="button" title="{{ cleanLang(__('lang.assigned_users')) }}"
                class="btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form data-toggle-action-tooltip"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/projects/'.$project->project_id.'/assigned') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.assigned_users')) }}"
                data-action-url="{{ urlResource('/projects/'.$project->project_id.'/assigned') }}"
                data-action-method="PUT" data-modal-size="modal-sm" data-action-ajax-class="ajax-request"
                data-action-ajax-class="" data-action-ajax-loading-target="projects-td-container">
                <i class="sl-icon-people"></i>
            </button>
            @endif
            @endif
            <!--view-->
            <a href="{{ _url('/projects/'.$project->project_id) }}" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
        <!--more button (team)-->
        @if(config('visibility.action_buttons_edit'))
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                title="{{ cleanLang(__('lang.more')) }}"
                class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                @include('pages.projects.views.common.dropdown-menu-team')
            </div>
        </span>
        @endif
    </td>
    @endif
</tr>
@endforeach
<!--each row-->