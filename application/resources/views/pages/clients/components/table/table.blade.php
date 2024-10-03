<div class="card count-{{ @count($clients ?? []) }}" id="clients-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($clients ?? []) > 0)
            <table id="clients-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
                <thead>
                    <tr>
                        <!--tableconfig_column_1 [client_id]-->
                        <th class="col_client_id {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_id" href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_id&sortorder=asc') }}">{{ cleanLang(__('lang.id')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>

                        <!--tableconfig_column_2 [client_company_name]-->
                        <th
                            class="col_client_company_name {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_company_name"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_company_name&sortorder=asc') }}">
                                {{ cleanLang(__('lang.company_name')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>

                        <!--tableconfig_column_3 [account_owner]-->
                        <th class="col_account_owner {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_account_owner"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=account_owner&sortorder=asc') }}">
                                {{ cleanLang(__('lang.account_owner')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>


                        <!--tableconfig_column_4 [count_pending_projects]-->
                        @if(config('visibility.modules.projects'))
                        <th
                            class="col_count_pending_projects {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_pending_projects"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_pending_projects&sortorder=asc') }}">
                                {{ cleanLang(__('lang.pending_projects')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        <!--tableconfig_column_5 [count_completed_projects]-->
                        @if(config('visibility.modules.projects'))
                        <th
                            class="col_count_completed_projects {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_completed_projects"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_completed_projects&sortorder=asc') }}">
                                {{ cleanLang(__('lang.completed_projects')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif


                        <!--tableconfig_column_6 [count_pending_tasks]-->
                        @if(config('visibility.modules.tasks'))
                        <th
                            class="col_count_pending_tasks {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_pending_tasks"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_pending_tasks&sortorder=asc') }}">
                                @lang('lang.pending_tasks')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_7 [count_completed_tasks]-->
                        @if(config('visibility.modules.tasks'))
                        <th
                            class="col_count_completed_tasks {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_completed_tasks"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_completed_tasks&sortorder=asc') }}">
                                @lang('lang.completed_tasks')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_8 [count_tickets_open]-->
                        @if(config('visibility.modules.tickets'))
                        <th
                            class="col_count_tickets_open {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_open"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_tickets_open&sortorder=asc') }}">
                                @lang('lang.open_tickets')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_9 [count_tickets_closed]-->
                        @if(config('visibility.modules.tickets'))
                        <th
                            class="col_count_tickets_closed {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_closed"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_tickets_closed&sortorder=asc') }}">
                                @lang('lang.closed_tickets')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_10 [sum_estimates_accepted]-->
                        @if(config('visibility.modules.estimates'))
                        <th
                            class="col_sum_estimates_accepted {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_estimates_accepted"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_estimates_accepted&sortorder=asc') }}">
                                @lang('lang.accepted_estimates')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_11 [sum_estimates_declined]-->
                        @if(config('visibility.modules.estimates'))
                        <th
                            class="col_sum_estimates_declined {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_estimates_declined"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_estimates_declined&sortorder=asc') }}">
                                @lang('lang.declined_estimates')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_12 [sum_invoices_all]-->
                        @if(config('visibility.modules.invoices'))
                        <th
                            class="col_sum_invoices_all_x {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_invoices_all_x"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_invoices_all_x&sortorder=asc') }}">
                                {{ cleanLang(__('lang.invoices')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        <!--tableconfig_column_13 [sum_all_payments]-->
                        @if(config('visibility.modules.payments'))
                        <th
                            class="col_sum_all_payments {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_all_payments"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_all_payments&sortorder=asc') }}">
                                @lang('lang.payments')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>

                        @endif

                        <!--tableconfig_column_14 [sum_outstanding_balance]-->
                        @if(config('visibility.modules.invoices'))
                        <th
                            class="col_sum_outstanding_balance {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_outstanding_balance"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_outstanding_balance&sortorder=asc') }}">
                                @lang('lang.unpaid_invoices')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_15 [sum_subscriptions_active]-->
                        @if(config('visibility.modules.subscriptions'))
                        <th
                            class="col_sum_subscriptions_active {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_subscriptions_active"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_subscriptions_active&sortorder=asc') }}">
                                @lang('lang.subscriptions')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_16 [count_proposals_accepted]-->
                        @if(config('visibility.modules.proposals'))
                        <th
                            class="col_count_proposals_accepted {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_proposals_accepted"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_proposals_accepted&sortorder=asc') }}">
                                @lang('lang.accepted_proposals')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_17 [count_proposals_declined]-->
                        @if(config('visibility.modules.proposals'))
                        <th
                            class="col_count_proposals_declined {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_proposals_declined"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_proposals_declined&sortorder=asc') }}">
                                @lang('lang.declined_proposals')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_18 [sum_contracts]-->
                        @if(config('visibility.modules.contracts'))
                        <th class="col_sum_contracts {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_contracts"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_contracts&sortorder=asc') }}">
                                @lang('lang.contracts')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_ 19[sum_hours_worked]-->
                        @if(config('visibility.modules.timesheets'))
                        <th
                            class="col_sum_hours_worked {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_hours_worked"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_hours_worked&sortorder=asc') }}">
                                @lang('lang.hours_worked')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_20 [count_tickets_open]-->
                        @if(config('visibility.modules.tickets'))
                        <th
                            class="col_count_tickets_open {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_open"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_tickets_open&sortorder=asc') }}">
                                @lang('lang.open_tickets')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_21 [count_tickets_closed]-->
                        @if(config('visibility.modules.tickets'))
                        <th
                            class="col_count_tickets_closed {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_closed"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_tickets_closed&sortorder=asc') }}">
                                @lang('lang.closed_tickets')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>
                        @endif

                        <!--tableconfig_column_22 [count_users]-->
                        <th class="col_count_users {{ config('table.tableconfig_column_22') }} tableconfig_column_22"><a
                                class="js-ajax-ux-request js-list-sorting" id="sort_count_users"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=count_users&sortorder=asc') }}">
                                @lang('lang.users')<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a></th>


                        <!--tableconfig_column_23 [tags]-->
                        <th class="clients_col_tags {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
                            <a href="javascript:void(0)">{{ cleanLang(__('lang.tags')) }}</a>
                        </th>

                        <!--tableconfig_column_24 [category]-->
                        <th class="col_category {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_category" href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=category&sortorder=asc') }}">
                                {{ cleanLang(__('lang.category')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a>
                        </th>

                        <!--tableconfig_column_25 [status]-->
                        <th class="col_status {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">
                                {{ cleanLang(__('lang.status')) }}<span class="sorting-icons"><i
                                        class="ti-arrows-vertical"></i></span></a>
                        </th>

                        <!--actions-->
                        @if(config('visibility.action_column'))
                        <th class="col_action with-table-config-icon"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a>

                            <!--[tableconfig]-->
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-clients">
                                    <i class="sl-icon-settings">
                                    </i>
                                </span>
                            </div>
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody id="clients-td-container">
                    <!--ajax content here-->
                    @include('pages.clients.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                            @include('misc.load-more-button')
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif @if (@count($clients ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>