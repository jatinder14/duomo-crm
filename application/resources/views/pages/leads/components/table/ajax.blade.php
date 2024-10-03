@foreach($leads as $lead)
<!--each row-->
<tr id="lead_{{ $lead->lead_id }}">
    @if(config('visibility.leads_col_checkboxes'))
    <td class="leads_col_checkbox checkitem" id="leads_col_checkbox_{{ $lead->lead_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-leads-{{ $lead->lead_id }}" name="ids[{{ $lead->lead_id }}]"
                class="listcheckbox listcheckbox-leads filled-in chk-col-light-blue"
                data-actions-container-class="leads-checkbox-actions-container">
            <label for="listcheckbox-leads-{{ $lead->lead_id }}"></label>
        </span>
    </td>
    @endif


    <!--tableconfig_column_1 [lead_firstname lead_lastname]-->
    <td class="col_lead_firstname {{ config('table.tableconfig_column_1') }} tableconfig_column_1" id="leads_col_contact_{{ $lead->lead_id }}">
        <span title="{{ $lead->full_name }}">
            <a class="show-modal-button reset-card-modal-form js-ajax-ux-request" data-toggle="modal"
            href="javascript:void(0)" data-target="#cardModal" data-url="{{ urlResource('/leads/'.$lead->lead_id) }}"
            data-loading-target="main-top-nav-bar" id="table_lead_title_{{ $lead->lead_id }}">
            <span title="{{ $lead->lead_title }}">{{ str_limit($lead->full_name, 15) }}</span>
        </span>
    </td>


    <!--tableconfig_column_2 [lead_title]-->
    <td class="col_lead_title {{ config('table.tableconfig_column_2') }} tableconfig_column_2" id="leads_col_title_{{ $lead->lead_id }}">
        <span title="{{ $lead->lead_title }}">
        <a class="show-modal-button reset-card-modal-form js-ajax-ux-request" data-toggle="modal"
            href="javascript:void(0)" data-target="#cardModal" data-url="{{ urlResource('/leads/'.$lead->lead_id) }}"
            data-loading-target="main-top-nav-bar" id="table_lead_title_{{ $lead->lead_id }}">
            <span title="{{ $lead->lead_title }}">{{ str_limit($lead->lead_title, 20) }}</span></a>
        </span>
    </td>


    <!--tableconfig_column_3 [lead_created]-->
    <td class="col_lead_created {{ config('table.tableconfig_column_3') }} tableconfig_column_3" id="leads_col_date_{{ $lead->lead_id }}">
        {{ runtimeDate($lead->lead_created) }}
    </td>


    <!--tableconfig_column_4 [lead_value]-->
    <td class="col_lead_value {{ config('table.tableconfig_column_4') }} tableconfig_column_4" id="leads_col_value_{{ $lead->lead_id }}">
        {{ runtimeMoneyFormat($lead->lead_value) }}
    </td>



    <!--tableconfig_column_6 [lead_assigned]-->
    <td class="leads_col_assigned {{ config('table.tableconfig_column_6') }} tableconfig_column_6" id="leads_col_assigned_{{ $lead->lead_id }}">
        <!--assigned users-->
        @if(count($lead->assigned ?? []) > 0)
        @foreach($lead->assigned->take(2) as $user)
        <img src="{{ $user->avatar }}" data-toggle="tooltip" title="{{ $user->first_name }}" data-placement="top"
            alt="{{ $user->first_name }}" class="img-circle avatar-xsmall">
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--assigned users-->
        <!--more users-->
        @if(count($lead->assigned ?? []) > 2)
        @php $more_users_title = __('lang.assigned_users'); $users = $lead->assigned; @endphp
        @include('misc.more-users')
        @endif
        <!--more users-->
    </td>


    <!--tableconfig_column_7 [lead_category_name]-->
    <td class="col_lead_category_name {{ config('table.tableconfig_column_7') }} tableconfig_column_7" id="col_lead_category_name_{{ $lead->lead_id }}">
        <span title="{{ $lead->category_name }}">
            {{ str_limit($lead->category_name ?? '---', 15) }}
        </span>
    </td>



    <!--tableconfig_column_5 [lead_status]-->
    <td class="col_lead_status {{ config('table.tableconfig_column_5') }} tableconfig_column_5" id="leads_col_stage_{{ $lead->lead_id }}">
        <span class="label {{ bootstrapColors($lead->leadstatus->leadstatus_color ?? '', 'label') }}">
            <!--notes: alternatve lang for lead status will need to be added manally by end user in lang files-->
            {{ runtimeLang($lead->leadstatus->leadstatus_title ?? '') }}</span>

        <!--archived-->
        @if($lead->lead_active_state == 'archived' && runtimeArchivingOptions())
        <span class="label label-icons label-icons-default" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.archived')"><i class="ti-archive"></i></span>
        @endif
    </td>
    
    <!--tableconfig_column_8 [lead_company_name]-->
    <td class="col_lead_company_name {{ config('table.tableconfig_column_8') }} tableconfig_column_8" id="col_lead_company_name_{{ $lead->lead_id }}">
        <span title="{{ $lead->lead_company_name }}">{{ str_limit($lead->lead_company_name ?? '---', 15) }}</span>
    </td>


    <!--tableconfig_column_9 [lead_email]-->
    <td class="col_lead_email {{ config('table.tableconfig_column_9') }} tableconfig_column_9" id="col_lead_email_{{ $lead->lead_id }}">
        {{ $lead->lead_email ?? '---' }}
    </td>


    <!--tableconfig_column_10 [lead_phone]-->
    <td class="col_lead_phone {{ config('table.tableconfig_column_10') }} tableconfig_column_10" id="col_lead_phone_{{ $lead->lead_id }}">
        {{ $lead->lead_phone ?? '---' }}
    </td>


    <!--tableconfig_column_11 [lead_job_position]-->
    <td class="col_lead_job_position {{ config('table.tableconfig_column_11') }} tableconfig_column_11" id="col_lead_job_position_{{ $lead->lead_id }}">
        <span title="{{ $lead->lead_job_position }}">
            {{ str_limit($lead->lead_job_position ?? '---', 15) }}
        </span>
    </td>


    <!--tableconfig_column_12 [lead_city]-->
    <td class="col_lead_city {{ config('table.tableconfig_column_12') }} tableconfig_column_12" id="col_lead_city_{{ $lead->lead_id }}">
        {{ $lead->lead_city ?? '---' }}
    </td>


    <!--tableconfig_column_13 [lead_state]-->
    <td class="col_lead_state {{ config('table.tableconfig_column_13') }} tableconfig_column_13" id="col_lead_state_{{ $lead->lead_id }}">
        {{ $lead->lead_state ?? '---' }}
    </td>


    <!--tableconfig_column_14 [lead_zip]-->
    <td class="col_lead_zip {{ config('table.tableconfig_column_14') }} tableconfig_column_14" id="col_lead_zip_{{ $lead->lead_id }}">
        {{ $lead->lead_zip ?? '---' }}
    </td>


    <!--tableconfig_column_15 [lead_country]-->
    <td class="col_lead_country {{ config('table.tableconfig_column_15') }} tableconfig_column_15" id="col_lead_country_{{ $lead->lead_id }}">
        <span title="{{ $lead->lead_country }}">
            {{ str_limit($lead->lead_country ?? '---', 15) }}
        </span>
    </td>


    <!--tableconfig_column_16 [lead_last_contacted]-->
    <td class="col_lead_last_contacted {{ config('table.tableconfig_column_16') }} tableconfig_column_16" id="col_lead_last_contacted_{{ $lead->lead_id }}">
        {{ runtimeDate($lead->lead_last_contacted) }}
    </td>


    <!--tableconfig_column_17 [lead_converted_by_userid]-->
    <td class="col_lead_converted_by_userid {{ config('table.tableconfig_column_17') }} tableconfig_column_17" id="col_lead_converted_by_userid_{{ $lead->lead_id }}">
        <span title="{{ $lead->converted_by_full_name }}">
            {{ str_limit($lead->converted_by_full_name ?? '---', 15) }}
        </span>
    </td>


    <!--tableconfig_column_18 [lead_converted_date]-->
    <td class="col_lead_converted_date {{ config('table.tableconfig_column_18') }} tableconfig_column_18" id="lead_converted_date_{{ $lead->lead_id }}">
        {{ runtimeDate($lead->lead_converted_date) }}
    </td>


    <!--tableconfig_column_19 [lead_source]-->
    <td class="col_lead_source {{ config('table.tableconfig_column_19') }} tableconfig_column_19" id="col_lead_source_{{ $lead->lead_id }}">
        <span title="{{ $lead->lead_source }}">
            {{ str_limit($lead->lead_source ?? '---', 15) }}
        </span>
    </td>

    <!--action button-->
    <td class="leads_col_action actions_column" id="leads_col_action_{{ $lead->lead_id }}">
        <span class="list-table-action dropdown font-size-inherit">
            @if(config('visibility.action_buttons_delete'))
            <!--[delete]-->
            @if($lead->permission_delete_lead)
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/leads/{{ $lead->lead_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled  {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-trash"></i></span>
            @endif
            @endif
            <!--send email-->
            <button type="button" title="@lang('lang.send_email')"
                class="data-toggle-action-tooltip btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ url('/appwebmail/compose?view=modal&webmail_template_type=leads&resource_type=lead&resource_id='.$lead->lead_id) }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.send_email')"
                data-action-url="{{ url('/appwebmail/send') }}" data-action-method="POST" data-modal-size="modal-xl"
                data-action-ajax-loading-target="leads-td-container">
                <i class="ti-email display-inline-block m-t-3"></i>
            </button>

            @if(auth()->user()->role->role_assign_leads == 'yes')
            <button type="button" title="{{ cleanLang(__('lang.assigned_users')) }}"
                class="btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form data-toggle-action-tooltip"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/leads/'.$lead->lead_id.'/assigned') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.assigned_users')) }}"
                data-action-url="{{ urlResource('/leads/'.$lead->lead_id.'/assigned') }}"
                data-action-method="PUT" data-modal-size="modal-sm" data-action-ajax-class="ajax-request"
                data-action-ajax-class="" data-action-ajax-loading-target="leads-td-container">
                <i class="sl-icon-people"></i>
            </button>
            @endif
            <!--view-->
            <button type="button" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm show-modal-button reset-card-modal-form js-ajax-ux-request"
                data-toggle="modal" data-target="#cardModal" data-url="{{ urlResource('/leads/'.$lead->lead_id) }}"
                data-loading-target="main-top-nav-bar">
                <i class="ti-new-window"></i>
            </button>
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
                <!--change category-->
                @if($lead->permission_edit_lead)
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                    data-url="{{ url('/leads/change-category') }}"
                    data-action-url="{{ urlResource('/leads/change-category?id='.$lead->lead_id) }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.change_category')) }}</a>
                <!--change status-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="{{ cleanLang(__('lang.change_status')) }}"
                    data-url="{{ urlResource('/leads/'.$lead->lead_id.'/change-status') }}"
                    data-action-url="{{ urlResource('/leads/'.$lead->lead_id.'/change-status') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.change_status')) }}</a>

                <!--archive-->
                @if($lead->lead_active_state == 'active' && runtimeArchivingOptions())
                <a class="dropdown-item confirm-action-info"
                    data-confirm-title="{{ cleanLang(__('lang.archive_lead')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="PUT"
                    data-url="{{ urlResource('/leads/'.$lead->lead_id.'/archive') }}">
                    {{ cleanLang(__('lang.archive')) }}
                </a>
                @endif

                <!--activate-->
                @if($lead->lead_active_state == 'archived' && runtimeArchivingOptions())
                <a class="dropdown-item confirm-action-info"
                    data-confirm-title="{{ cleanLang(__('lang.restore_lead')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="PUT"
                    data-url="{{ urlResource('/leads/'.$lead->lead_id.'/activate') }}">
                    {{ cleanLang(__('lang.restore')) }}
                </a>
                @endif


                @else
                <span class="small">--- no options avaiable</span>
                @endif
            </div>
        </span>
        @endif
        <!--more button-->
    </td>
</tr>
@endforeach
<!--each row-->