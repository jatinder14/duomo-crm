<div class="card count-{{ @count($leaves ?? []) }}" id="leaves-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($leaves ?? []) > 0)
                <table id="leaves-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            @if (config('visibility.leaves_col_checkboxes'))
                                <th class="list-checkbox-wrapper">
                                    <!--list checkbox-->
                                    <span class="list-checkboxes display-inline-block w-px-20">
                                        <input type="checkbox" id="listcheckbox-leaves" name="listcheckbox-leaves"
                                            class="listcheckbox-all filled-in chk-col-light-blue"
                                            data-actions-container-class="leaves-checkbox-actions-container"
                                            data-children-checkbox-class="listcheckbox-leaves"
                                            {{ runtimeDisabledleavesCheckboxes(config('visibility.leaves_disable_actions')) }}>
                                        <label for="listcheckbox-leaves"></label>
                                    </span>
                                </th>
                            @endif
                            <!--team_member-->
                            <th class="leaves_col_team_member"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_team_member" href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=team_member&sortorder=asc') }}">{{ cleanLang(__('lang.team_member')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>
                            <!--date-->
                            <th class="leaves_col_date"><a class="js-ajax-ux-request js-list-sorting" id="sort_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=date&sortorder=asc') }}">{{ cleanLang(__('lang.date')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>

                            <!--leave_category-->
                            <th class="leaves_col_leave_category"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_leave_category" href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=leave_category&sortorder=asc') }}">{{ cleanLang(__('lang.category')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--reason-->
                            <th class="leaves_col_reason"><a class="js-ajax-ux-request js-list-sorting" id="sort_reason"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=reason&sortorder=asc') }}">{{ cleanLang(__('lang.reason')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                            <!--leave_status-->
                            <th class="leaves_col_leave_status"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_leave_status" href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=leave_status&sortorder=asc') }}">{{ cleanLang(__('lang.status')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--comment-->
                            <th class="leaves_col_comment"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_comment" href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=comment&sortorder=asc') }}">{{ cleanLang(__('lang.comment')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            <!--leave_created-->
                            <th class="leaves_col_leave_created"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_leave_created" href="javascript:void(0)"
                                    data-url="{{ urlResource('/leaves?action=sort&orderby=leave_created&sortorder=asc') }}">{{ cleanLang(__('lang.created')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>

                            @if (config('visibility.leaves_col_action'))
                                <th class="leaves_col_action"><a
                                        href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="leaves-td-container">
                        <!--ajax content here-->
                        @include('pages.leaves.components.table.ajax')
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
                @endif @if (@count($leaves ?? []) == 0)
                    <!--nothing found-->
                    @include('notifications.no-results-found')
                    <!--nothing found-->
                @endif
        </div>
    </div>
</div>
