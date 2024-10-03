<div class="count-{{ @count($projects ?? []) }}" id="projects-view-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive list-table-wrapper">
                @if (@count($projects ?? []) > 0)
                <table id="projects-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            @if(config('visibility.projects_col_checkboxes'))
                            <th class="list-checkbox-wrapper">
                                <!--list checkbox-->
                                <span class="list-checkboxes display-inline-block w-px-20">
                                    <input type="checkbox" id="listcheckbox-projects" name="listcheckbox-projects"
                                        class="listcheckbox-all filled-in chk-col-light-blue"
                                        data-actions-container-class="projects-checkbox-actions-container"
                                        data-children-checkbox-class="listcheckbox-projects">
                                    <label for="listcheckbox-projects"></label>
                                </span>
                            </th>
                            @endif
                            <!--tableconfig_column_1 [project_id]-->
                            <th class="projects_col_id {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_id"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_id&sortorder=asc') }}">{{ cleanLang(__('lang.id')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--tableconfig_column_2 [project_title]-->
                            <th
                                class="projects_col_project {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_title"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_title&sortorder=asc') }}">{{ cleanLang(__('lang.title')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--tableconfig_column_3 [client_company_name]-->
                            @if(config('visibility.projects_col_client'))
                            <th
                                class="projects_col_client {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_client"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_client&sortorder=asc') }}">{{ cleanLang(__('lang.client')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            @endif
                            <!--tableconfig_column_4 [project_date_start]-->
                            <th
                                class="projects_col_start_date {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_date_start"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_date_start&sortorder=asc') }}">{{ cleanLang(__('lang.start_date')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--tableconfig_column_5 [project_date_due]-->
                            <th
                                class="projects_col_end_date {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_date_due"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_date_due&sortorder=asc') }}">{{ cleanLang(__('lang.due_date')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--tableconfig_column_6 [tags]-->
                            @if(config('visibility.projects_col_tags'))
                            <th
                                class="projects_col_tags {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                                <a href="javascript:void(0)">{{ cleanLang(__('lang.tags')) }}</a>
                            </th>
                            @endif
                            <!--tableconfig_column_7 [project_progress]-->
                            <th
                                class="projects_col_progress {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_progress"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_progress&sortorder=asc') }}">{{ cleanLang(__('lang.progress')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>

                            <!--tableconfig_column_8 [count_pending_tasks]-->
                            <th
                                class="col_count_pending_tasks {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_count_pending_tasks"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=count_pending_tasks&sortorder=asc') }}">@lang('lang.pending_tasks')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_9 [count_completed_tasks]-->
                            <th
                                class="col_count_completed_tasks {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_count_completed_tasks"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=count_completed_tasks&sortorder=asc') }}">@lang('lang.completed_tasks')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_10 [sum_invoices_all]-->
                            <th
                                class="col_sum_invoices_all {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_invoices_all"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=sum_invoices_all&sortorder=asc') }}">@lang('lang.invoices')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_11 [sum_all_payments]-->
                            <th
                                class="col_sum_all_payments {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_all_payments"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=sum_all_payments&sortorder=asc') }}">@lang('lang.payments')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_12 [sum_outstanding_balance]-->
                            <th
                                class="col_sum_outstanding_balance {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_outstanding_balance"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=sum_outstanding_balance&sortorder=asc') }}">@lang('lang.unpaid_invoices')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_13 [project_billing_type]-->
                            <th
                                class="col_project_billing_type {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_billing_type"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_billing_type&sortorder=asc') }}">@lang('lang.billing')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_14 [project_billing_estimated_hours]-->
                            <th
                                class="col_project_billing_estimated_hours {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_billing_estimated_hours"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_billing_estimated_hours&sortorder=asc') }}">@lang('lang.estimated_hours')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_15 [project_billing_costs_estimate]-->
                            <th
                                class="col_project_billing_costs_estimate {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_billing_costs_estimate"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_billing_costs_estimate&sortorder=asc') }}">@lang('lang.estimated_cost')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_16 [sum_hours]-->
                            <th class="col_sum_hours {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_hours"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=sum_hours&sortorder=asc') }}">@lang('lang.hours_worked')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_17 [sum_expenses]-->
                            <th
                                class="col_sum_expenses {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_expenses"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=sum_expenses&sortorder=asc') }}">@lang('lang.expenses')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_18 [count_files]-->
                            <th
                                class="col_count_files {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_count_files"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=count_files&sortorder=asc') }}">@lang('lang.files')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_19 [count_tickets_open]-->
                            <th
                                class="count_tickets_open {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_open"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=count_tickets_open&sortorder=asc') }}">@lang('lang.open_tickets')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_20 [count_tickets_closed]-->
                            <th
                                class="col_count_tickets_closed {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_count_tickets_closed"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=count_tickets_closed&sortorder=asc') }}">@lang('lang.closed_tickets')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--tableconfig_column_21 [assigned]-->
                            @if(config('visibility.projects_col_team'))
                            <th
                                class="projects_col_team {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                                <a href="javascript:void(0)">{{ cleanLang(__('lang.team')) }}</a></th>
                            @endif
                            <!--tableconfig_column_22 [project_status]-->
                            <th
                                class="projects_col_status {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
                                <a class="js-ajax-ux-request js-list-sorting" id="sort_project_status"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/projects?action=sort&orderby=project_status&sortorder=asc') }}">{{ cleanLang(__('lang.status')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            @if(config('visibility.projects_col_actions'))
                            <th class="projects_col_action with-table-config-icon"><a
                                    href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a>

                                <!--[tableconfig]-->
                                <div class="table-config-icon">
                                    <span class="text-default js-toggle-table-config-panel"
                                        data-target="table-config-projects">
                                        <i class="sl-icon-settings">
                                        </i>
                                    </span>
                                </div>
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="projects-td-container">
                        <!--ajax content here-->
                        @include('pages.projects.views.list.table.ajax')
                        <!--/ajax content here-->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="20">
                                <!--load more button-->
                                @include('misc.load-more-button')
                                <!--/load more button-->
                            </td>
                        </tr>
                    </tfoot>
                </table>
                @endif @if (@count($projects ?? []) == 0)
                <!--nothing found-->
                @include('notifications.no-results-found')
                <!--nothing found-->
                @endif
            </div>
        </div>
    </div>
</div>