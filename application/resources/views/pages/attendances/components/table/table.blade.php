<div class="card count-{{ @count($attendances ?? []) }}" id="attendances-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($attendances ?? []) > 0)
                <table id="attendances-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            @if (config('visibility.attendances_col_checkboxes'))
                                <th class="list-checkbox-wrapper">
                                    <!--list checkbox-->
                                    <span class="list-checkboxes display-inline-block w-px-20">
                                        <input type="checkbox" id="listcheckbox-attendances"
                                            name="listcheckbox-attendances"
                                            class="listcheckbox-all filled-in chk-col-light-blue"
                                            data-actions-container-class="attendances-checkbox-actions-container"
                                            data-children-checkbox-class="listcheckbox-attendances"
                                            {{ runtimeDisabledAttendancesCheckboxes(config('visibility.attendances_disable_actions')) }}>
                                        <label for="listcheckbox-attendances"></label>
                                    </span>
                                </th>
                            @endif
                            <!--team_member-->
                            <th class="attendances_col_team_member"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_team_member" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=team_member&sortorder=asc') }}">{{ cleanLang(__('lang.team_member')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--date-->
                            <th class="attendances_col_date"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_date" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=date&sortorder=asc') }}">{{ cleanLang(__('lang.date')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>

                            <!--in time-->
                            <th class="attendances_col_in_time"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_in_time" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=in_time&sortorder=asc') }}">{{ cleanLang(__('lang.in_time')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--out time-->
                            <th class="attendances_col_out_time"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_out_time" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=out_time&sortorder=asc') }}">{{ cleanLang(__('lang.out_time')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--reason-->
                            <th class="attendances_col_reason"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_reason" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=reason&sortorder=asc') }}">{{ cleanLang(__('lang.reason')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--attendance_status-->
                            <th class="attendances_col_attendance_status"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_attendance_status" href="javascript:void(0)"
                                    data-url="{{ urlResource('/attendances?action=sort&orderby=attendance_status&sortorder=asc') }}">{{ cleanLang(__('lang.status')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                            @if (config('visibility.attendances_col_action'))
                                <th class="attendances_col_action"><a
                                        href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="attendances-td-container">
                        <!--ajax content here-->
                        @include('pages.attendances.components.table.ajax')
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
                @endif @if (@count($attendances ?? []) == 0)
                    <!--nothing found-->
                    @include('notifications.no-results-found')
                    <!--nothing found-->
                @endif
        </div>
    </div>
</div>
