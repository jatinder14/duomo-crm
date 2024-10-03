<div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($leads ?? []) > 0)
            <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        @if(config('visibility.leads_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-leads" name="listcheckbox-leads"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="leads-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-leads">
                                <label for="listcheckbox-leads"></label>
                            </span>
                        </th>
                        @endif

                        <!--tableconfig_column_1 [lead_firstname lead_lastname]-->
                        <th class="col_lead_firstname {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_firstname"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_firstname&sortorder=asc') }}">{{ cleanLang(__('lang.contact')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_2 [lead_title]-->
                        <th class="col_lead_title {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_title" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_title&sortorder=asc') }}">{{ cleanLang(__('lang.title')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_3 [lead_created]-->
                        <th class="col_lead_created {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_created"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_created&sortorder=asc') }}">@lang('lang.created')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_4 [lead_value]-->
                        <th class="col_lead_value {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_value" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_value&sortorder=asc') }}">@lang('lang.value')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_6 [lead_assigned]-->
                        <th class="col_lead_assigned {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <a href="javascript:void(0);">{{ cleanLang(__('lang.assigned')) }}</a></th>


                        <!--tableconfig_column_7 [lead_category_name]-->
                        <th
                            class="col_lead_category_name {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_category_name"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_category_name&sortorder=asc') }}">@lang('lang.category')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_5 [lead_status]-->
                        <th class="col_lead_status {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_status&sortorder=asc') }}">@lang('lang.status')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_8 [lead_company_name]-->
                        <th
                            class="col_lead_company_name {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_company_name"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_company_name&sortorder=asc') }}">@lang('lang.company')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_9 [lead_email]-->
                        <th class="col_lead_email {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_email" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_email&sortorder=asc') }}">@lang('lang.email')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_10 [lead_phone]-->
                        <th class="col_lead_phone {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_phone" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_phone&sortorder=asc') }}">@lang('lang.phone')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_11 [lead_job_position]-->
                        <th
                            class="col_lead_job_position {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_job_position"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_job_position&sortorder=asc') }}">@lang('lang.position')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_12 [lead_city]-->
                        <th class="col_lead_city {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_city" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_city&sortorder=asc') }}">@lang('lang.city')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_13 [lead_state]-->
                        <th class="col_lead_state {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_state" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_state&sortorder=asc') }}">@lang('lang.state')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_14 [lead_zip]-->
                        <th class="col_lead_zip {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_zip" href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_zip&sortorder=asc') }}">@lang('lang.zipcode')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>

                        <!--tableconfig_column_15 [lead_country]-->
                        <th class="col_lead_country {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_country"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_country&sortorder=asc') }}">@lang('lang.country')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_16 [lead_last_contacted]-->
                        <th
                            class="col_lead_last_contacted {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_last_contacted"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_last_contacted&sortorder=asc') }}">@lang('lang.last_contacted')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_17 [lead_converted_by_userid]-->
                        <th
                            class="col_lead_converted_by_userid {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_converted_by_userid"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_converted_by_userid&sortorder=asc') }}">@lang('lang.converted_by')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_18 [lead_converted_date]-->
                        <th
                            class="col_lead_converted_date {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_converted_date"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_converted_date&sortorder=asc') }}">@lang('lang.date_converted')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--tableconfig_column_19 [lead_source]-->
                        <th class="col_lead_source {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_lead_source"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/leads?action=sort&orderby=lead_source&sortorder=asc') }}">@lang('lang.source')<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </a>
                        </th>


                        <!--[actions]-->
                        <th class="leads_col_action with-table-config-icon"><a href="javascript:void(0)">
                                {{ cleanLang(__('lang.action')) }}</a>

                            <!--[tableconfig]-->
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-leads">
                                    <i class="sl-icon-settings">
                                    </i>
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="leads-td-container">
                    <!--ajax content here-->
                    @include('pages.leads.components.table.ajax')
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
            @endif @if (@count($leads ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>