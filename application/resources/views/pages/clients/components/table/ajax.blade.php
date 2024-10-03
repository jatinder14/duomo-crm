@foreach($clients as $client)
<!--each row-->
<tr id="client_{{ $client->client_id }}">

    <!--tableconfig_column_1 [client_id]-->
    <td class="clients_col_id {{ config('table.tableconfig_column_1') }} tableconfig_column_1"
        id="clients_col_id_{{ $client->client_id }}">
        {{ $client->client_id }}
    </td>

    <!--tableconfig_column_2 [client_company_name]-->
    <td class="clients_col_company {{ config('table.tableconfig_column_2') }} tableconfig_column_2"
        id="clients_col_id_{{ $client->client_id }}">
        <a href="/clients/{{ $client->client_id ?? '' }}">{{ str_limit($client->client_company_name, 35) }}</a>
    </td>

    <!--tableconfig_column_3 [account_owner]-->
    <td class="clients_col_account_owner {{ config('table.tableconfig_column_3') }} tableconfig_column_3"
        id="clients_col_account_owner_{{ $client->client_id }}">
        <img src="{{ getUsersAvatar($client->avatar_directory, $client->avatar_filename) }}" alt="user"
            class="img-circle avatar-xsmall">
        <span>{{ $client->first_name ?? '---' }}</span>
    </td>


    <!--tableconfig_column_4 [count_pending_projects]-->
    @if(config('visibility.modules.projects'))
    <td class="col_count_pending_projects {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
        {{ $client->count_pending_projects ?? '0' }}
    </td>
    @endif

    <!--tableconfig_column_5 [count_completed_projects]-->
    @if(config('visibility.modules.projects'))
    <td class="col_count_completed_projects {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
        {{ $client->count_completed_projects ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_6 [count_pending_tasks]-->
    @if(config('visibility.modules.tasks'))
    <td class="col_count_pending_tasks {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
        {{ $client->count_pending_tasks ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_7 [count_completed_tasks]-->
    @if(config('visibility.modules.tasks'))
    <td class="col_count_completed_tasks {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
        {{ $client->count_completed_tasks ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_8 [count_tickets_open]-->
    @if(config('visibility.modules.tickets'))
    <td class="col_count_tickets_open {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
        {{ $client->count_tickets_open ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_9 [count_tickets_closed]-->
    @if(config('visibility.modules.tickets'))
    <td class="col_count_tickets_closed {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
        {{ $client->count_tickets_closed ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_10 [sum_estimates_accepted]-->
    @if(config('visibility.modules.estimates'))
    <td class="col_sum_estimates_accepted {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
        {{ runtimeMoneyFormat($client->sum_estimates_accepted) }}
    </td>
    @endif


    <!--tableconfig_column_11 [sum_estimates_declined]-->
    @if(config('visibility.modules.estimates'))
    <td class="col_sum_estimates_declined {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
        {{ runtimeMoneyFormat($client->sum_estimates_declined) }}
    </td>
    @endif


    <!--tableconfig_column_12 [sum_invoices_all]-->
    @if(config('visibility.modules.invoices'))
    <td class="col_sum_invoices_all {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
        {{ runtimeMoneyFormat($client->sum_invoices_all_x) }}
    </td>
    @endif


    <!--tableconfig_column_13 [sum_all_payments]-->
    @if(config('visibility.modules.payments'))
    <td class="col_sum_all_payments {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
        {{ runtimeMoneyFormat($client->sum_all_payments) }}
    </td>
    @endif


    <!--tableconfig_column_14 [sum_outstanding_balance]-->
    @if(config('visibility.modules.invoices'))
    <td class="col_sum_outstanding_balance {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
        {{ runtimeMoneyFormat($client->sum_outstanding_balance) }}
    </td>
    @endif



    <!--tableconfig_column_15 [sum_subscriptions_active]-->
    @if(config('visibility.modules.subscriptions'))
    <td class="col_sum_subscriptions_active {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
        {{ runtimeMoneyFormat($client->sum_subscriptions_active) }}
    </td>
    @endif


    <!--tableconfig_column_16 [count_proposals_accepted]-->
    @if(config('visibility.modules.proposals'))
    <td class="col_sum_proposals_accepted {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
        {{ $client->count_proposals_accepted ?? 0 }}
    </td>
    @endif


    <!--tableconfig_column_17 [count_proposals_declined]-->
    @if(config('visibility.modules.proposals'))
    <td class="col_sum_proposals_declined {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
        {{ $client->count_proposals_accepted ?? 0 }}
    </td>
    @endif


    <!--tableconfig_column_18 [sum_contracts]-->
    @if(config('visibility.modules.contracts'))
    <td class="col_sum_contracts {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
        {{ runtimeMoneyFormat($client->sum_contracts) }}
    </td>
    @endif


    <!--tableconfig_column_ 19[sum_hours_worked]-->
    @if(config('visibility.modules.timesheets'))
    <td class="col_sum_hours_worked {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
        {{ runtimeSecondsWholeHours($client->sum_hours_worked) }}:{{ runtimeSecondsWholeMinutesZero($client->sum_hours_worked) }}
    </td>
    @endif

    <!--tableconfig_column_20 [count_tickets_open]-->
    @if(config('visibility.modules.tickets'))
    <td class="col_count_tickets_open {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
        {{ $client->count_tickets_open ?? '0' }}
    </td>
    @endif


    <!--tableconfig_column_21 [count_tickets_closed]-->
    @if(config('visibility.modules.tickets'))
    <td class="col_count_tickets_closed {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
        {{ $client->count_tickets_closed ?? '0' }}
    </td>
    @endif

    <!--tableconfig_column_22 [count_users]-->
    <td class="col_count_users {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
        {{ $client->count_users ?? '0' }}
    </td>


    <!--tableconfig_column_23 [tags]-->
    <td class="clients_col_tags {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
        <!--tag-->
        @if(count($client->tags ?? []) > 0)
        @foreach($client->tags->take(1) as $tag)
        <span class="label label-outline-default">{{ str_limit($tag->tag_title, 15) }}</span>
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        @if(count($client->tags ?? []) > 1)
        @php $tags = $client->tags; @endphp
        @include('misc.more-tags')
        @endif
        <!--more tags-->

    </td>

    <!--tableconfig_column_24 [category]-->
    <td class="col_category {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
        <!--category-->
        <span class="label label-outline-default">{{ str_limit($client->category_name, 15) }}</span>
        <!--category-->
    </td>

    <!--tableconfig_column_25 [status]-->
    <th class="col_status {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
        <span class="label {{ runtimeClientStatusLabel($client->client_status) }}">{{
            runtimeLang($client->client_status) }}</span>
        </td>


        <!--actions-->
        @if(config('visibility.action_column'))
    <td class="clients_col_action actions_column" id="clients_col_action_{{ $client->client_id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_client')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/clients/'.$client->client_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/clients/'.$client->client_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_client')) }}"
                data-action-url="{{ urlResource('/clients/'.$client->client_id.'?ref=list') }}" data-action-method="PUT"
                data-action-ajax-loading-target="clients-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif

            <!--send email-->
            <button type="button" title="@lang('lang.send_email')"
                class="data-toggle-action-tooltip btn btn-outline-warning btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ url('/appwebmail/compose?view=modal&webmail_template_type=clients&resource_type=client&resource_id='.$client->client_id) }}"
                data-loading-target="commonModalBody" data-modal-title="@lang('lang.send_email')"
                data-action-url="{{ url('/appwebmail/send') }}" data-action-method="POST" data-modal-size="modal-xl"
                data-action-ajax-loading-target="clients-td-container">
                <i class="ti-email display-inline-block m-t-3"></i>
            </button>

            <a href="/clients/{{ $client->client_id ?? '' }}" class="btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
        <!--more button (hidden)-->
        <span class="list-table-action dropdown hidden font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="ti-new-window"></i> {{ cleanLang(__('lang.view_details')) }}</a>
            </div>
        </span>
        <!--more button-->
    </td>
    @endif

</tr>
@endforeach
<!--each row-->