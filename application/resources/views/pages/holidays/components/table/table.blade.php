<div class="card count-{{ @count($holidays ?? []) }}" id="holidays-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($holidays ?? []) > 0)
                <table id="holidays-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            @if (config('visibility.holidays_col_checkboxes'))
                                <th class="list-checkbox-wrapper">
                                    <!--list checkbox-->
                                    <span class="list-checkboxes display-inline-block w-px-20">
                                        <input type="checkbox" id="listcheckbox-holidays" name="listcheckbox-holidays"
                                            class="listcheckbox-all filled-in chk-col-light-blue"
                                            data-actions-container-class="holidays-checkbox-actions-container"
                                            data-children-checkbox-class="listcheckbox-holidays"
                                            {{ runtimeDisabledholidaysCheckboxes(config('visibility.holidays_disable_actions')) }}>
                                        <label for="listcheckbox-holidays"></label>
                                    </span>
                                </th>
                            @endif


                            <!--date-->
                            <th class="holidays_col_holiday_date"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_holiday_date" href="javascript:void(0)"
                                    data-url="{{ urlResource('/holidays?action=sort&orderby=holiday_date&sortorder=asc') }}">{{ cleanLang(__('lang.date')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>

                            <!--holiyday_description-->
                            <th class="holidays_col_holiyday_description"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_holiyday_description" href="javascript:void(0)"
                                    data-url="{{ urlResource('/holidays?action=sort&orderby=holiyday_description&sortorder=asc') }}">{{ cleanLang(__('lang.description')) }}<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>


                            @if (config('visibility.holidays_col_action'))
                                <th class="holidays_col_action"><a
                                        href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="holidays-td-container">
                        <!--ajax content here-->
                        @include('pages.holidays.components.table.ajax')
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
                @endif @if (@count($holidays ?? []) == 0)
                    <!--nothing found-->
                    @include('notifications.no-results-found')
                    <!--nothing found-->
                @endif
        </div>
    </div>
</div>
